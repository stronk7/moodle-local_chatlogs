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

if (has_capability('moodle/site:config', get_system_context())) {
    $temp = new admin_settingpage('local_chatlogs_settings', get_string('pluginname', 'local_chatlogs'), 'moodle/site:config');

    if ($cohorts = $DB->get_records_menu('cohort', array('contextid' => 50), 'name', 'id, name')) {
        $temp->add(new admin_setting_configselect('local_chatlogs/cohortid', get_string('developercohort', 'local_chatlogs'),
            get_string('developercohortdescription', 'local_chatlogs'), null, $cohorts));
    }

    $ADMIN->add('localplugins', $temp);
}
