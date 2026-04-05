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
        //
        // On the frontpage, $COURSE is the SITEID course, so this lists
        // exactly the Check-in activities that live on the frontpage
        // itself — see concept doc §10.21 for the rationale (cross-course
        // linking rejected due to enrolment/capability mismatch). If the
        // list is empty we render a direct "create a new activity here"
        // hint instead of the dropdown alone, otherwise admins on a fresh
        // frontpage see only a lonely "Choose…" entry and have to guess
        // why.
        $activities = [];
        $modinfo = get_fast_modinfo($COURSE);
        foreach ($modinfo->get_instances_of('elediacheckin') as $cm) {
            if (!$cm->uservisible) {
                continue;
            }
            $activities[$cm->id] = format_string($cm->name);
        }

        $options = [0 => get_string('choosedots')] + $activities;
        $mform->addElement('select', 'config_cmid',
            get_string('linkedactivity', 'block_elediacheckin'), $options);
        $mform->setType('config_cmid', PARAM_INT);
        $mform->addHelpButton('config_cmid', 'linkedactivity', 'block_elediacheckin');

        // Empty-state hint: no Check-in activity exists in the current
        // course (or frontpage) yet — show a direct "add activity" link
        // so the admin doesn't have to hunt for the right page.
        if (empty($activities) && has_capability('moodle/course:manageactivities',
                \core\context\course::instance($COURSE->id))) {
            $addurl = new moodle_url('/course/modedit.php', [
                'add'    => 'elediacheckin',
                'course' => $COURSE->id,
                'return' => 0,
            ]);
            $hint = \html_writer::div(
                get_string('noactivityinthiscourse', 'block_elediacheckin')
                    . ' ' . \html_writer::link($addurl,
                        get_string('createactivitynow', 'block_elediacheckin'),
                        ['class' => 'btn btn-sm btn-primary ms-2']),
                'alert alert-warning'
            );
            $mform->addElement('static', 'config_cmid_empty', '', $hint);
        }

        $mform->addElement('selectyesno', 'config_showpreview',
            get_string('showpreview', 'block_elediacheckin'));
        $mform->setDefault('config_showpreview', 0);
        $mform->addHelpButton('config_showpreview', 'showpreview', 'block_elediacheckin');
    }
}
