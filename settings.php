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
 * Settings for local_chatlogs plugin
 *
 * @package     local_chatlogs
 * @copyright   2012 Dan Poltawski <dan@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/local/chatlogs/adminlib.php');

if (has_capability('moodle/site:config', context_system::instance())) {
    $temp = new admin_settingpage('local_chatlogs_settings', get_string('pluginname', 'local_chatlogs'), 'moodle/site:config');

    $temp->add(new local_chatlogs_cohort_selector('local_chatlogs/cohortid', get_string('developercohort', 'local_chatlogs'),
        get_string('developercohortdescription', 'local_chatlogs'), 0, null));

    $ADMIN->add('localplugins', $temp);
}
