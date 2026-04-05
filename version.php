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
 * Version metadata for block_elediacheckin.
 *
 * @package    block_elediacheckin
 * @copyright  2026 eLeDia GmbH <info@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'block_elediacheckin';
$plugin->version   = 2026040504;
$plugin->requires  = 2024100700; // Moodle 4.5.
$plugin->maturity  = MATURITY_ALPHA;
$plugin->release   = '0.3.0';

// This block is a lightweight wrapper around mod_elediacheckin's service layer -
// the activity module must be present for the block to work. Bumped to
// 2026040513 because the block now calls activity_pool::pick_random() which
// was added to the mod in that release.
$plugin->dependencies = [
    'mod_elediacheckin' => 2026040513,
];
