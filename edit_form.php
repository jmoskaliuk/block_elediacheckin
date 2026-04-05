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
 * Instance configuration form for block_elediacheckin.
 *
 * @package    block_elediacheckin
 * @copyright  2026 eLeDia GmbH <info@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Per-instance settings form.
 *
 * The block is scoped to one mod_elediacheckin activity in the current
 * course — we list the available instances as a drop-down rather than
 * asking the user to paste a course-module id.
 */
class block_elediacheckin_edit_form extends block_edit_form {

    /**
     * Defines the instance configuration fields.
     *
     * @param MoodleQuickForm $mform
     */
    protected function specific_definition($mform): void {
        global $COURSE;

        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_elediacheckin'));
        $mform->setType('config_title', PARAM_TEXT);

        // Build "pick a Check-in activity in this course" drop-down.
        $options = [0 => get_string('choosedots')];
        $modinfo = get_fast_modinfo($COURSE);
        foreach ($modinfo->get_instances_of('elediacheckin') as $cm) {
            if (!$cm->uservisible) {
                continue;
            }
            $options[$cm->id] = format_string($cm->name);
        }

        $mform->addElement('select', 'config_cmid',
            get_string('linkedactivity', 'block_elediacheckin'), $options);
        $mform->setType('config_cmid', PARAM_INT);
        $mform->addHelpButton('config_cmid', 'linkedactivity', 'block_elediacheckin');

        $mform->addElement('selectyesno', 'config_showpreview',
            get_string('showpreview', 'block_elediacheckin'));
        $mform->setDefault('config_showpreview', 0);
        $mform->addHelpButton('config_showpreview', 'showpreview', 'block_elediacheckin');
    }
}
