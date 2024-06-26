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
 * Capability definitions for the chat logs plugin
 *
 * @package     local_chatlogs
 * @category    access
 * @copyright   2012 Dan Poltawski <dan@moodle.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [

    // Ability to view chatlogs irrespective of cohort memebership.
    'local/chatlogs:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => [],
    ],

    // Ability to view chatlogs if developer cohort.
    'local/chatlogs:viewifdeveloper' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => [],
    ],

];
