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
 * English language strings for block_elediacheckin.
 *
 * @package    block_elediacheckin
 * @copyright  2026 eLeDia GmbH <info@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname']                  = 'Check-in';
$string['elediacheckin:addinstance']   = 'Add a new Check-in block';
$string['elediacheckin:myaddinstance'] = 'Add a Check-in block to the Dashboard';

$string['blocktitle']          = 'Block title';
$string['linkedactivity']      = 'Linked Check-in activity';
$string['linkedactivity_help'] = 'Pick the Check-in activity the block should launch. Only activities in the current course are listed (on the front page: only activities that live on the front page itself). Add an activity first if the list is empty — you can run several Check-in activities side-by-side (e.g. one for team reflection, one for leadership) and link each block to a different one.';
$string['noactivityinthiscourse'] = 'There is no Check-in activity in this course yet for the block to launch.';
$string['createactivitynow']      = 'Create a Check-in activity now';
$string['showpreview']         = 'Show question preview';
$string['showpreview_help']    = 'If enabled, the block renders a random question from the linked activity above the launch buttons. The preview respects the activity\'s ziele, categories and content-language settings.';

$string['openactivity']   = 'Open Check-in';
$string['noquestions']    = 'No questions available.';
$string['notconfigured']  = 'This block is not yet linked to a Check-in activity. Edit the block to choose one.';

$string['privacy:metadata'] = 'The Check-in block does not store any personal data. It only displays questions that originate from the mod_elediacheckin content repository.';
