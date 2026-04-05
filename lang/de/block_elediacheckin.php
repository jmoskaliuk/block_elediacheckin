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
 * German language strings for block_elediacheckin.
 *
 * @package    block_elediacheckin
 * @copyright  2026 eLeDia GmbH <info@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname']                  = 'Check-in';
$string['elediacheckin:addinstance']   = 'Neuen Check-in-Block hinzufügen';
$string['elediacheckin:myaddinstance'] = 'Check-in-Block zum Dashboard hinzufügen';

$string['blocktitle']          = 'Blocktitel';
$string['linkedactivity']      = 'Verknüpfte Check-in-Aktivität';
$string['linkedactivity_help'] = 'Wählen Sie die Check-in-Aktivität aus, die der Block starten soll. Es werden nur Aktivitäten aus dem aktuellen Kurs angezeigt (auf der Startseite: nur Aktivitäten, die auf der Startseite selbst liegen). Legen Sie zuerst eine Aktivität an, wenn die Liste leer ist — Sie können mehrere Check-in-Aktivitäten nebeneinander betreiben (z.B. eine für Team-Reflexion und eine für Führungskräfte) und pro Block eine auswählen.';
$string['noactivityinthiscourse'] = 'In diesem Kurs gibt es noch keine Check-in-Aktivität, die der Block starten könnte.';
$string['createactivitynow']      = 'Jetzt eine Check-in-Aktivität anlegen';
$string['showpreview']         = 'Fragen-Vorschau anzeigen';
$string['showpreview_help']    = 'Wenn aktiviert, zeigt der Block eine zufällige Frage aus der verknüpften Aktivität über den Start-Buttons an. Die Vorschau berücksichtigt die Fragetypen-, Kategorien- und Sprach-Einstellungen der Aktivität.';

$string['openactivity']   = 'Öffnen';
$string['noquestions']    = 'Keine Fragen verfügbar.';
$string['notconfigured']  = 'Dieser Block ist noch nicht mit einer Check-in-Aktivität verknüpft. Bearbeite den Block, um eine auszuwählen.';

$string['privacy:metadata'] = 'Der Check-in-Block speichert keine personenbezogenen Daten. Er zeigt ausschließlich Fragen aus dem mod_elediacheckin-Inhalts-Repository an.';
