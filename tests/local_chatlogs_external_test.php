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

defined('MOODLE_INTERNAL') || die();

global $CFG;


/**
 * Chatlogs webservice tests
 *
 * @package local_chatlogs
 * @copyright 2016 Dan Poltawski
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_chatlogs_external_test extends externallib_advanced_testcase {


    /**
     * Setup function- we will create a course and add an assign instance to it.
     */
    protected function setUp() {
        global $DB;

        $this->resetAfterTest(true);

        // Insert some chatlog data.
        $conversations = [
            ['conversationid' => '1', 'timestart' => '1358138860', 'timeend' => '1358140681', 'messagecount' => 2],
            ['conversationid' => '2', 'timestart' => '1358142505', 'timeend' => '1358142507', 'messagecount' => 1],
        ];
        $DB->insert_records('local_chatlogs_conversations', $conversations);
        $messages = [
            ['conversationid' => '1', 'fromemail' => 'poltawski@moodle.org', 'timesent' => '1358138860', 'timejava' => '1358138860', 'message' => 'Testing'],
            ['conversationid' => '1', 'fromemail' => 'poltawski@moodle.org', 'timesent' => '1358138958', 'timejava' => '1358138958', 'message' => 'Testing'],
            ['conversationid' => '2', 'fromemail' => 'poltawski@moodle.org', 'timesent' => '1358142505', 'timejava' => '1358142505', 'message' => '123'],
        ];
        $DB->insert_records('local_chatlogs_messages', $messages);
    }

    public function test_get_conversation_list_no_permissions() {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $this->setExpectedException('moodle_exception', 'Sorry, but you do not currently have permissions to do that (View chat logs)');
        $result = local_chatlogs_external::get_conversation_list();
    }

    public function test_get_conversation_list() {
        $this->setAdminUser();

        $result = local_chatlogs_external::get_conversation_list();
        $result = external_api::clean_returnvalue(local_chatlogs_external::get_conversation_list_returns(), $result);
        $this->assertArrayHasKey('conversations', $result);
        $conversations = $result['conversations'];
        $this->assertCount(2, $conversations);
    }

    public function test_get_conversation_messages_no_permissions() {
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $this->setExpectedException('moodle_exception', 'Sorry, but you do not currently have permissions to do that (View chat logs)');
        $result = local_chatlogs_external::get_conversation_messages(1);
    }

    public function test_get_conversation_messages() {
        $this->setAdminUser();

        $result = local_chatlogs_external::get_conversation_messages(1);
        $result = external_api::clean_returnvalue(local_chatlogs_external::get_conversation_messages_returns(), $result);

        $this->assertArrayHasKey('messages', $result);
        $conversations = $result['messages'];
        $this->assertCount(2, $conversations);

        $result = local_chatlogs_external::get_conversation_messages(2);
        $result = external_api::clean_returnvalue(local_chatlogs_external::get_conversation_messages_returns(), $result);

        $this->assertArrayHasKey('messages', $result);
        $conversations = $result['messages'];
        $this->assertCount(1, $conversations);

        $result = local_chatlogs_external::get_conversation_messages(3);
        $result = external_api::clean_returnvalue(local_chatlogs_external::get_conversation_messages_returns(), $result);

        $this->assertArrayHasKey('messages', $result);
        $conversations = $result['messages'];
        $this->assertCount(0, $conversations);
    }
}
