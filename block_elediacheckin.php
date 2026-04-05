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
 * Main block class for block_elediacheckin.
 *
 * The block is a thin launcher for an existing mod_elediacheckin activity
 * in the same course. It does not own any questions or configuration of
 * its own beyond "which activity should I launch and should I render a
 * preview card on top of the launch buttons?". All content resolution
 * (ziele / categories / contentlang) is delegated to the activity's own
 * view.php and present.php.
 *
 * @package    block_elediacheckin
 * @copyright  2026 eLeDia GmbH <info@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use mod_elediacheckin\local\service\activity_pool;

/**
 * Compact check-in block - launches a mod_elediacheckin activity in the
 * same course and optionally renders a preview question card.
 */
class block_elediacheckin extends block_base {

    /**
     * Initialises the block metadata.
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_elediacheckin');
    }

    /**
     * Allows per-instance configuration.
     *
     * @return bool
     */
    public function instance_allow_config(): bool {
        return true;
    }

    /**
     * Allows the same block to be added multiple times on a page.
     *
     * @return bool
     */
    public function instance_allow_multiple(): bool {
        return true;
    }

    /**
     * Declares on which pages this block can appear.
     *
     * Course pages are the primary use case (the block launches a
     * course-scoped Check-in activity). The frontpage is also allowed
     * because Moodle treats the frontpage as a regular course (SITEID)
     * — admins can therefore place a Check-in activity on the frontpage
     * and add this block to launch it site-wide. The per-instance edit
     * form uses `get_fast_modinfo($COURSE)`, which resolves to the
     * frontpage course on site-index, so the dropdown correctly offers
     * frontpage-scoped Check-in activities when configuring the block
     * there. Dashboard/admin/other mod pages stay off to avoid noise.
     *
     * Both `site` and `site-index` are set to true: Moodle core uses
     * `site-index` as the canonical page-type pattern for the frontpage,
     * while `site` is accepted as a shorthand in a handful of core
     * blocks. Setting both makes the intent explicit in both idioms.
     *
     * @return array
     */
    public function applicable_formats(): array {
        return [
            'course-view' => true,
            'site'        => true,
            'site-index'  => true,
            'my'          => false,
            'mod'         => false,
            'admin'       => false,
        ];
    }

    /**
     * Builds the block content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $OUTPUT, $COURSE;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text   = '';
        $this->content->footer = '';

        $config = $this->config ?? new stdClass();
        $cmid   = (int) ($config->cmid ?? 0);

        // No activity chosen yet: show the hint + edit pencil prompt.
        if ($cmid <= 0) {
            $this->content->text = html_writer::tag('p',
                get_string('notconfigured', 'block_elediacheckin'),
                ['class' => 'text-muted mb-0']);
            return $this->content;
        }

        // Resolve the linked course module. Catch any lookup failure so the
        // block degrades gracefully if the activity was deleted.
        try {
            $cm = get_coursemodule_from_id('elediacheckin', $cmid, 0, false, MUST_EXIST);
        } catch (\Throwable $e) {
            $this->content->text = html_writer::tag('p',
                get_string('notconfigured', 'block_elediacheckin'),
                ['class' => 'text-muted mb-0']);
            return $this->content;
        }

        // Capability check: do not render launchers to users who cannot see
        // the activity.
        $modcontext = \core\context\module::instance($cm->id);
        if (!has_capability('mod/elediacheckin:view', $modcontext)) {
            return $this->content;
        }

        $instance = $this->fetch_instance_row($cm->instance);

        // Optional preview question — rendered above the launch buttons.
        // Resolved BEFORE the launch URLs so we can pin the currently-
        // displayed card into the URL params (?q=<externalid>&activeziel=
        // <ziel>). That way, clicking "Open Check-in" or "Open as popup"
        // opens the same card the user was just looking at, instead of
        // rolling a fresh random one on the target page.
        $preview = null;
        if (!empty($config->showpreview) && $instance !== null) {
            $preview = $this->resolve_preview_question($instance, $COURSE);
        }

        $viewparams  = ['id' => $cm->id];
        $popupparams = ['id' => $cm->id, 'layout' => 'popup'];
        if ($preview !== null && !empty($preview['externalid'])) {
            $viewparams['q']           = $preview['externalid'];
            $viewparams['activeziel']  = $preview['ziel'];
            $popupparams['q']          = $preview['externalid'];
            $popupparams['activeziel'] = $preview['ziel'];
        }
        $viewurl  = new moodle_url('/mod/elediacheckin/view.php', $viewparams);
        $popupurl = new moodle_url('/mod/elediacheckin/present.php', $popupparams);

        $templatecontext = [
            'instanceid'     => $this->instance->id,
            'activityname'   => $instance ? format_string($instance->name) : format_string($cm->name),
            'viewurl'        => $viewurl->out(false),
            'popupurl'       => $popupurl->out(false),
            'hasquestion'    => !empty($preview),
            'question'       => $preview,
            'showpreview'    => !empty($config->showpreview),
            'stropen'        => get_string('openactivity', 'block_elediacheckin'),
            'strpopup'       => get_string('openpopup', 'elediacheckin'),
            'strfullscreen'  => get_string('openfullscreen', 'elediacheckin'),
            'strnone'        => get_string('noquestions', 'block_elediacheckin'),
        ];

        $this->content->text = $OUTPUT->render_from_template(
            'block_elediacheckin/content',
            $templatecontext
        );

        return $this->content;
    }

    /**
     * Fetch the activity instance row, tolerating missing records.
     *
     * @param int $instanceid
     * @return \stdClass|null
     */
    private function fetch_instance_row(int $instanceid): ?\stdClass {
        global $DB;
        $row = $DB->get_record('elediacheckin', ['id' => $instanceid]);
        return $row ?: null;
    }

    /**
     * Resolve a random preview question honouring the activity's ziele,
     * categories, content-language AND the teacher's per-activity own
     * questions. Delegates to activity_pool so the block preview sees
     * exactly the same pool the activity view would — own questions
     * included.
     *
     * The first configured ziel is used for the preview. Language
     * resolution mirrors view.php: configured → current user → any.
     *
     * @param \stdClass $instance The elediacheckin DB row.
     * @param \stdClass $course   The current course record.
     * @return array|null         Template context for the preview card, or null.
     */
    private function resolve_preview_question(\stdClass $instance, \stdClass $course): ?array {
        $ziele = array_values(array_filter(array_map('trim',
            explode(',', (string) ($instance->ziele ?? '')))));
        if (empty($ziele)) {
            $ziele = ['checkin'];
        }
        $activeziel = $ziele[0];

        $langcandidates = [];
        $configured = (string) ($instance->contentlang ?? '');
        if ($configured === '_auto_') {
            $langcandidates[] = current_language();
        } else if ($configured === '_course_') {
            $langcandidates[] = !empty($course->lang) ? $course->lang : current_language();
        } else if ($configured !== '') {
            $langcandidates[] = $configured;
        }
        $langcandidates[] = current_language();

        $question = activity_pool::pick_random($instance, $activeziel, $langcandidates);
        if (!$question) {
            return null;
        }

        // Own questions: teacher-authored plain text, escape aggressively.
        // Bundle questions: trusted JSON content, allow simple HTML.
        $rendered = !empty($question->isown)
            ? format_text($question->frage, FORMAT_PLAIN)
            : format_text($question->frage, FORMAT_HTML);

        $zielforlabel = !empty($question->ziel) ? $question->ziel : $activeziel;
        $zielkey = 'ziel_' . $zielforlabel;

        return [
            'frage'      => $rendered,
            'ziel'       => $activeziel,
            'externalid' => (string) ($question->externalid ?? ''),
            'ziellabel'  => get_string_manager()->string_exists($zielkey, 'elediacheckin')
                ? get_string($zielkey, 'elediacheckin')
                : ucfirst($zielforlabel),
        ];
    }
}
