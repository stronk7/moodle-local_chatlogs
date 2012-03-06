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
 * Displays list of conversations or a specific covnersation depending on params
 *
 * @package     local_chatlogs
 * @copyright   2012 Dan Poltawski <dan@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/local/chatlogs/locallib.php');
require_once($CFG->dirroot.'/local/chatlogs/lib.php');

$conversationid = optional_param('conversationid', 0, PARAM_INT);

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_url(new moodle_url('/local/chatlogs/index.php'));

$PAGE->set_title(get_string('pluginname', 'local_chatlogs'));
$PAGE->set_heading(get_string('pluginname', 'local_chatlogs'));

require_login(null, false);

if (!local_chatlogs_can_access()) {
    print_error('nopermissions', 'error');
    die;
}

echo $OUTPUT->header();
if ($conversationid) {
    $conversation = new local_chatlogs_conversation($conversationid);
    $conversation->render();
} else {
    echo $OUTPUT->heading(get_string('developerconversations', 'local_chatlogs'));
    $table = new local_chatlogs_converations_table('chatlogs-table');
    $table->define_baseurl($PAGE->url);
    $table->out(20, true);
}

echo $OUTPUT->footer();
