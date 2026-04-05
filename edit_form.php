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
 */
class block_elediacheckin_edit_form extends block_edit_form {

    /**
     * Defines the instance configuration fields.
     *
     * @param MoodleQuickForm $mform
     */
    protected function specific_definition($mform): void {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_elediacheckin'));
        $mform->setType('config_title', PARAM_TEXT);

        $displaymodes = [
            'link' => get_string('displaymode_link', 'block_elediacheckin'),
            'mini' => get_string('displaymode_mini', 'block_elediacheckin'),
        ];
        $mform->addElement('select', 'config_displaymode',
            get_string('displaymode', 'block_elediacheckin'), $displaymodes);
        $mform->setDefault('config_displaymode', 'link');

        $mform->addElement('text', 'config_cmid',
            get_string('linkedactivitycmid', 'block_elediacheckin'), ['size' => '8']);
        $mform->setType('config_cmid', PARAM_INT);
        $mform->addHelpButton('config_cmid', 'linkedactivitycmid', 'block_elediacheckin');

        $questionmodes = [
            'both'     => get_string('mode_both', 'elediacheckin'),
            'checkin'  => get_string('mode_checkin', 'elediacheckin'),
            'checkout' => get_string('mode_checkout', 'elediacheckin'),
        ];
        $mform->addElement('select', 'config_questionmode',
            get_string('questionmode', 'block_elediacheckin'), $questionmodes);
        $mform->setDefault('config_questionmode', 'both');
        $mform->hideIf('config_questionmode', 'config_displaymode', 'eq', 'link');

        $mform->addElement('text', 'config_categories',
            get_string('categories', 'elediacheckin'), ['size' => '48']);
        $mform->setType('config_categories', PARAM_TEXT);
        $mform->hideIf('config_categories', 'config_displaymode', 'eq', 'link');

        $mform->addElement('text', 'config_contentlang',
            get_string('contentlang', 'elediacheckin'), ['size' => '8']);
        $mform->setType('config_contentlang', PARAM_LANG);
        $mform->hideIf('config_contentlang', 'config_displaymode', 'eq', 'link');

        $mform->addElement('selectyesno', 'config_showfullviewlink',
            get_string('showfullviewlink', 'block_elediacheckin'));
        $mform->setDefault('config_showfullviewlink', 1);
        $mform->hideIf('config_showfullviewlink', 'config_displaymode', 'eq', 'link');
    }
}
