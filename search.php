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
 * Searches chat history based on specific terms
 *
 * @package     local_chatlogs
 * @copyright   2012 Dan Poltawski <dan@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/local/chatlogs/locallib.php');

$searchterm = optional_param('q', '', PARAM_TEXT);

require_login(SITEID, false);
local_chatlogs_require_capability();

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_url(new moodle_url('/local/chatlogs/search.php', array('q' => $searchterm)));

$PAGE->set_title(get_string('searchchat', 'local_chatlogs'));
$PAGE->set_heading(get_string('searchchat', 'local_chatlogs'));


echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('searchchat', 'local_chatlogs'));

echo local_chatlogs_search_table::form($searchterm);

if (!empty($searchterm)) {
    $table = new local_chatlogs_search_table('fooo', $searchterm);
    $table->define_baseurl($PAGE->url);
    $table->out(50, true);
}

echo $OUTPUT->footer();
