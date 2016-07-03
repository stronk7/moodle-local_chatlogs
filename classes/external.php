<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is the external API for chatlogs.
 *
 * @package    local_chatlogs
 * @copyright  2016 Dan Poltawski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");
require_once("$CFG->dirroot/local/chatlogs/locallib.php");

class local_chatlogs_external extends external_api {

    /**
     * Returns description of get_conversation_list() parameters.
     *
     * @return external_function_parameters
     */
    public static function get_conversation_list_parameters() {
          return new external_function_parameters(
              array(
                'page' => new external_value(PARAM_INT, 'The page to display results from.', VALUE_DEFAULT, 0),
                'perpage' => new external_value(PARAM_INT, 'Number of conversations per page.', VALUE_DEFAULT, 50),
              )
          );
    }

    /**
     * Get list of chatlogs conversations
     *
     * @param  int $page Which page to start results from
     * @param  int $perpage How many results per page
     * @return array conversation list
     */
    public static function get_conversation_list($page = 0, $perpage = 50) {
        global $DB;
        $params = self::validate_parameters(self::get_conversation_list_parameters(),
          ['page' => $page, 'perpage' => $perpage]);

        $context = context_system::instance();
        self::validate_context($context);
        local_chatlogs_require_capability();

        return ['conversations' => $DB->get_records('local_chatlogs_conversations', [], 'timeend DESC',
          'conversationid, timestart, timeend, messagecount', $params['page'], $params['perpage'])];
    }

    /**
     * Returns description of get_conversation_list() result value.
     *
     * @return external_description
     */
    public static function get_conversation_list_returns() {
        return new external_single_structure(
          array(
            'conversations' => new external_multiple_structure(
            new external_single_structure(
            array(
              'conversationid' => new external_value(PARAM_INT, 'Conversation id'),
              'timestart' => new external_value(PARAM_INT, 'Start time'),
              'timeend' => new external_value(PARAM_INT, 'End time'),
              'messagecount' => new external_value(PARAM_INT, 'Message count')
            )))));
    }
    /**
     * Returns description of get_conversation_messages() parameters.
     *
     * @return external_function_parameters
     */
    public static function get_conversation_messages_parameters() {
        return new external_function_parameters( ['conversationid' => new external_value(PARAM_INT, 'conversationid')]);
    }

    /**
     * Gets the list of messages from a conversation.
     *
     * @param  int $conversationid The id of the convrsation
     * @return array conversation messages
     */
    public static function get_conversation_messages($conversationid) {
        global $DB;
        $params = self::validate_parameters(self::get_conversation_messages_parameters(), ['conversationid' => $conversationid]);

        $context = context_system::instance();
        self::validate_context($context);
        local_chatlogs_require_capability();

        return ['messages' =>
          $DB->get_records('local_chatlogs_messages',
              ['conversationid' => $params['conversationid']], 'timesent',
          'id, fromnick, timesent, message')];
    }

    /**
     * Returns description of get_conversation_messages() result value.
     *
     * @return external_description
     */
    public static function get_conversation_messages_returns() {
        return new external_single_structure(
          array(
            'messages' => new external_multiple_structure(
            new external_single_structure(
            array(
              'id' => new external_value(PARAM_INT, 'Conversation id'),
              'fromnick' => new external_value(PARAM_RAW, 'from'),
              'timesent' => new external_value(PARAM_INT, 'time'),
              'message' => new external_value(PARAM_RAW, 'Message count')
            )))));
    }
}
