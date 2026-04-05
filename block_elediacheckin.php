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
 * @package    block_elediacheckin
 * @copyright  2026 eLeDia GmbH <info@eledia.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use mod_elediacheckin\local\service\question_provider;

/**
 * Compact check-in block - displays a single question or a launcher link.
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
     * @return array
     */
    public function applicable_formats(): array {
        return [
            'course-view'    => true,
            'site'           => true,
            'my'             => true,
            'mod'            => false,
            'admin'          => false,
        ];
    }

    /**
     * Builds the block content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text   = '';
        $this->content->footer = '';

        $config = $this->config ?? new stdClass();
        $mode   = $config->displaymode ?? 'link';

        if ($mode === 'link') {
            $this->content->text = $this->render_link_mode($config);
            return $this->content;
        }

        // mini generator mode.
        $provider = new question_provider();
        $question = $provider->get_random_question([
            'mode'       => $config->questionmode ?? 'both',
            'categories' => $config->categories ?? '',
            'lang'       => $config->contentlang ?? current_language(),
        ]);

        $templatecontext = [
            'instanceid'  => $this->instance->id,
            'hasquestion' => !empty($question),
            'question'    => $question,
            'strnew'      => get_string('newquestion', 'block_elediacheckin'),
            'strnone'     => get_string('noquestions', 'block_elediacheckin'),
            'fullviewurl' => $this->get_full_view_url($config),
            'strfullview' => get_string('fullview', 'block_elediacheckin'),
            'showfullviewlink' => !empty($config->showfullviewlink),
        ];

        $this->content->text = $OUTPUT->render_from_template(
            'block_elediacheckin/content',
            $templatecontext
        );

        return $this->content;
    }

    /**
     * Renders the link-only mode body.
     *
     * @param stdClass $config
     * @return string
     */
    private function render_link_mode(stdClass $config): string {
        $url = $this->get_full_view_url($config);
        if (!$url) {
            return html_writer::tag('p', get_string('notconfigured', 'block_elediacheckin'),
                ['class' => 'text-muted mb-0']);
        }
        return html_writer::link($url, get_string('openactivity', 'block_elediacheckin'),
            ['class' => 'btn btn-primary']);
    }

    /**
     * Resolves the URL of the referenced mod_elediacheckin activity, if any.
     *
     * @param stdClass $config
     * @return moodle_url|null
     */
    private function get_full_view_url(stdClass $config): ?moodle_url {
        if (empty($config->cmid)) {
            return null;
        }
        return new moodle_url('/mod/elediacheckin/view.php', ['id' => (int) $config->cmid]);
    }
}
