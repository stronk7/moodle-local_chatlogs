<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Imports logs from a simple json api provided by moodle-matrixbot.
 *
 * @package     local_chatlogs
 * @copyright   2024 onwards Eloy Lafuente (stronk7) {@link https://stronk7.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_chatlogs;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir .'/filelib.php');

/**
 * Imports logs from a simple json api provided by moodle-matrixbot.
 *
 * @package     local_chatlogs
 * @copyright   2024 onwards Eloy Lafuente (stronk7) {@link https://stronk7.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class matrix_importer {
    /** @var int Number of seconds between conversations to start a new one */
    const CONVERSATION_GAP = HOURSECS;
    /** @var string url to telegram logs api */
    protected $apiurl = '';
    /** @var string secret which allows access to the api */
    protected $secret = '';
    /** @var string matrix room name which logs we want to fetch */
    protected $matrixroom = '';
    /** @var array of user aliases which are already stored */
    protected $participantscache = [];

    /**
     * Constructor.
     *
     * @param string $apiurl url for telegram logs api
     * @param string $apisecret secret which allows access to the api
     * @param string $matrixroom matrix room name which logs we want to fetch
     */
    public function __construct(string $apiurl, string $apisecret, string $matrixroom) {
        $this->apiurl = $apiurl;
        $this->secret = $apisecret;
        $this->matrixroom = $matrixroom;
    }

    /**
     * Import the logs from the REST api.
     *
     * @return int number of logs imported
     */
    public function import() {
        global $DB;

        $transaction = $DB->start_delegated_transaction();

        $sql = "SELECT conversationid, timesent, timejava
                  FROM {local_chatlogs_messages}
                ORDER BY timejava DESC LIMIT 1";
        $lastmessage = $DB->get_record_sql($sql, null, IGNORE_MISSING);
        if (!$lastmessage) {
            $lastmessage = new \stdClass;
            $lastmessage->conversationid = 0;
            $lastmessage->timesent = 0;
            $lastmessage->timejava = 0;
        }

        $remotemessages = $this->get_logs_from_api($lastmessage->timejava);

        foreach ($remotemessages as $remotemessage) {
            $message = $this->format_matrix_message($remotemessage);
            $conversation = $this->get_or_create_conversation($message, $lastmessage);

            $message->conversationid = $conversation->conversationid;
            try {
                $message->id = $DB->insert_record('local_chatlogs_messages', $message);
            } catch (\dml_exception $e) {
                // Specific horrible handling to work around utf-8 multibyte problems
                // with mysql (emojis..) MDL-48228.
                if (strpos($e->error, 'Incorrect string value:') === false) {
                    // Different error. Throw.
                    throw $e;
                }
                // Replace the problematic 4-byte utf-8 chars.
                $message->message = self::replace4byte($message->message, '�');
                $message->fromnick = self::replace4byte($message->fromnick, '�');
                // Try again..
                $message->id = $DB->insert_record('local_chatlogs_messages', $message);
            }
            $this->record_partcipant($message);

            $lastmessage = $message;
        }

        // In previous implementation we handled 'orphaned' messages by moving them between conversations.
        // This is intentionally ignored for now as it seems to be the source of bugs and isn't clear
        // it's worth it (https://github.com/moodlehq/moodle-local_chatlogs/pull/8).
        $transaction->allow_commit();
        return count($remotemessages);
    }

    /**
     * Utility to strip probematic 4-byte utf8 chars for use in MDL-48228 workaround.
     *
     * Thanks https://stackoverflow.com/a/16496799
     *
     * @param string $string to strip 4-byte utf8 count_chars
     * @param string $replacement
     * @return string without the 4-byte utf8 chars
     */
    protected static function replace4byte($string, $replacement = '') {
        return preg_replace('%(?:
            \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
        )%xs', $replacement, $string);
    }

    /**
     * Call the logs api and return an array of chatlogs since the specified time
     *
     * @param int $milisecondtimestamp miliseconds since epoch timestamp to retrieve logs since
     * @return int number of logs imported
     */
    protected function get_logs_from_api(int $aftertimestamp ) {
        $url = new \moodle_url($this->apiurl);

        $json = $this->call_api($url, $aftertimestamp);
        $logs = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \moodle_exception("JSON response from API was invalid");
        }
        return $logs;
    }

    /**
     * Call the REST api and return the response
     *
     * @param \moodle_url $url to call
     * @return mixed response from server
     */
    protected function call_api(\moodle_url $url, int $aftertimestamp) {
        $data = json_encode([
            'room' => $this->matrixroom,
            'secret' => $this->secret,
            'aftertimestamp' => $aftertimestamp,
        ]);
        $curl = new \curl();
        $curl->setHeader('Content-Type: application/json');
        $response = $curl->post($url,  $data);
        if ($curl->info['http_code'] !== 200) {
            throw new \moodle_exception("Invalid response code from {$this->apiurl}: {$curl->info['http_code']}");
        }
        return $response;
    }

    /**
     * Format a chat message coming from the moodle-matrixbot api into the format the table expects.
     *
     * @param \stdClass $input chatlog entry from api
     * @return \stdClass chatlog entry formated for the local_chatlogs_messages table
     */
    protected function format_matrix_message($input) {
        $message = new \stdClass;
        $message->fromemail = $input->username;
        $message->fromnick = $input->fullname;
        $message->message = $input->message;
        $message->messageformat = $input->messageformat;
        $message->timejava = $input->timestamp; // Time since epoch in milliseconds.
        $message->timesent = intdiv($input->timestamp, 1000); // Time since epoch in seconds.

        // The following are fields which don't make as much sense in the post-jabber
        // world, and perhaps we'll get rid of?
        $message->fromplace = 'moodle-matrixbot-api';

        return $message;
    }

    /**
     * Gets or creates the local_chatlogs_conversations record for which the chatlog
     * message should be associated with.
     *
     * This function either create a new conversation record or retrieve the existing
     * conversation which the message should be associated with, depending on whether the
     * message is within self::CONVERSATION_GAP.
     *
     * @param \stdClass $message the message we are inserting into conversation
     * @param \stdClass $previousmessage the most recent message added to the logs
     * @return \stdClass conversation record
     */
    protected function get_or_create_conversation($message, $previousmessage) {
        global $DB;

        if ($message->timesent - $previousmessage->timesent < self::CONVERSATION_GAP) {
            // Part of existing conversation.
            $conversation = $DB->get_record('local_chatlogs_conversations',
                ['conversationid' => $previousmessage->conversationid], '*', MUST_EXIST);
            $conversation->timeend = $message->timesent;
            $conversation->messagecount = $conversation->messagecount + 1;
            $DB->update_record('local_chatlogs_conversations', $conversation);
        } else {
            // Create a new conversation.
            $conversation = new \stdClass;
            // The 'conversationid' is our own, manual id numbering..
            $conversation->conversationid = $previousmessage->conversationid + 1;
            $conversation->timestart = $message->timesent;
            $conversation->timeend = $message->timesent + 1;
            $conversation->messagecount = 1;
            $DB->insert_record('local_chatlogs_conversations', $conversation);
        }
        return $conversation;
    }

    /**
     * Records the chat participant in local_chatlogs_participants table.
     *
     * @param \stdClass $message the message we are inserting into conversation
     */
    protected function record_partcipant($message) {
        global $DB;
        if (isset($this->participantscache[$message->fromemail])) {
            return;
        }

        if ($DB->record_exists('local_chatlogs_participants', ['fromemail' => $message->fromemail])) {
            $this->participantscache[$message->fromemail] = true;
            return;
        }

        $participant = new \stdClass;
        $participant->fromemail = $message->fromemail;
        $participant->nickname = $message->fromnick;
        $DB->insert_record('local_chatlogs_participants', $participant);
        $this->participantscache[$message->fromemail] = true;
    }
}
