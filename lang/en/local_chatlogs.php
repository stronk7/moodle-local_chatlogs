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
 * Lang file for local_chatlogs plugin
 *
 * @package     local_chatlogs
 * @category    string
 * @copyright   2012 Dan Poltawski <dan@moodle.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['allconversations'] = 'All conversations';
$string['apisecret'] = 'API Secret';
$string['apisecretdescription'] = 'The shared secret needed to access the API.';
$string['apiurl'] = 'API URL';
$string['apiurldescription'] = 'The url to the Telegram/Matrix logbot api to retrieve room messages. When set this will stop the existing direct direct DB sync from operating';
$string['chatlogs:manage'] = 'Manage the developer chat logs plugin';
$string['chatlogs:view'] = 'View developer chat logs irrespective of cohort membership';
$string['chatlogs:viewifdeveloper'] = 'View developer chat logs if in developer cohort';
$string['developercohort'] = 'Developer cohort';
$string['developercohortdescription'] = 'Select the cohort which developers are in. Users in this cohort and with the local/chatlogs:viewifdeveloper capability will be able to view the chatlogs';
$string['developerconversations'] = 'Chat history';
$string['info'] = 'Info';
$string['infohistory'] = 'Historically, Moodle developers used a Jabber chat room for synchronous
discussions. Since 2017, the chat was moved to <a href="https://telegram.org/">Telegram</a>.
And later, in 2022, it was decided to move everything to <a href="https://matrix.org/">Matrix</a>.
Developers are encouraged to join the chat at <a href="https://telegram.me/moodledev">telegram.me/moodledev</a> or
<a href="https://matrix.to/#/#moodledev:moodle.com">matrix.to/#/#moodledev:moodle.com</a>.
Both rooms are bridged, so you can use either one.';
$string['jabberaliases'] = 'Aliases';
$string['jabberaliasesassign'] = 'Assign user';
$string['jabberfullname'] = 'Nick';
$string['jabberid'] = 'ID';
$string['matrixroom'] = 'Matrix room name';
$string['matrixroomdescription'] = 'Only needed for Matrix, the room name to sync messages from';
$string['pluginname'] = 'Developer chat';
$string['privacy:metadata:db:messages'] = 'Stores copies of developer chat discussions';
$string['privacy:metadata:db:messages:conversationid'] = 'Internal identifier of the conversation';
$string['privacy:metadata:db:messages:fromemail'] = 'Email identifier of the user';
$string['privacy:metadata:db:messages:fromnick'] = 'User\'s nickname';
$string['privacy:metadata:db:messages:fromplace'] = 'User\'s place';
$string['privacy:metadata:db:messages:message'] = 'Chat message contents';
$string['privacy:metadata:db:messages:timejava'] = 'Timestamp of the message in miliseconds since the start of UNIX epoch';
$string['privacy:metadata:db:messages:timesent'] = 'Timestamp of the message in seconds since the start of UNIX epoch';
$string['privacy:metadata:db:participants'] = 'Holds all known aliases of Moodle users';
$string['privacy:metadata:db:participants:fromemail'] = 'Email address';
$string['privacy:metadata:db:participants:nickname'] = 'Nickname';
$string['searchchat'] = 'Search chat history';
$string['searchmessages'] = 'Search messages';
$string['syncchatlogs'] = 'Sync chatlogs';
$string['viewchatlogs'] = 'View chat logs';
