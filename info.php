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
 * Displays info of how to get to the chat logs
 *
 * @package     local_chatlogs
 * @copyright   2012 Dan Poltawski <dan@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require($CFG->dirroot.'/local/chatlogs/locallib.php');
require($CFG->dirroot.'/local/chatlogs/lib.php');

require_login(SITEID, false);

if (!local_chatlogs_can_access()) {
    print_error('nopermissions', 'error');
    die;
}

$PAGE->set_pagelayout('standard');
$PAGE->set_url(new moodle_url('/local/chatlogs/info.php'));
$PAGE->set_title(get_string('info', 'local_chatlogs'));
$PAGE->set_heading(get_string('info', 'local_chatlogs'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('info', 'local_chatlogs'));
echo $OUTPUT->box_start();
?>
<p>Our Jabber chat room can be found at <strong>developers@conference.moodle.org</strong>. Currently it is restricted to
developers who have commited code to Moodle. Please do not publish this address otherwise we'll have to put a password on it.
Logs of these chats can be found in the past conversations link.</p> <p>You can join in using any Jabber-compatible client,
such as <a href="http://www.pidgin.im/">Pidgin</a>, <a href="http://www.miranda-im.org/">Miranda</a>,
<a href="http://www.adiumx.com">Adium</a> or even <a href="http://www.apple.com/macosx/features/ichat.html">iChat</a> on OS X.
You can use any Jabber account you may have.  For example, a Gmail account will work fine (although there is a known
problem with Google accounts that will scramble your recent history a bit when you first connect to the chat room).</p>
<p>If you need a new Jabber account, developers are welcome to create one for themselves in the moodle.org domain
using the create account feature in your Jabber client.  If you have trouble connecting, you may have explicitly
specify the connect server as <strong>talk.moodle.org</strong>, port 5222.  You can also use port 80 or 443
if you are behind a firewall.</p>
<?php 
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
