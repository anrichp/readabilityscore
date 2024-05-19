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
 * Block readabilityscore is defined here.
 *
 * @package     block_readabilityscore
 * @copyright   2024 Anrich Potgieter <anrichp@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/lib.php');

class block_readabilityscore extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_readabilityscore');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {

        global $PAGE;

        // Get current page content
        $text = $PAGE->get_renderer('core')->main_content();

        // Send text to readability score function
        $score = readability_score($stripped_text);

        //Display readability score
        $this->content = new stdClass();
        $this->content->text = 'Readability Score: ' . $score;

        // Add button to open dashboard page
        $button = new stdClass();
        $button->url = new moodle_url('/blocks/readabilityscore/dashboard.php');
        $button->text = 'Dashboard';
        $button->class = 'btn btn-primary';
        $this->content->buttons[] = $button;
        $output = '<div class="readability-score-wrapper">'; // Create a wrapper div
        $output .= 'Readability Score: ' . $score;
        $output .= '<a href="' . $button->url . '" class="' . $button->class . '">' . $button->text . '</a>';
        $output .= '</div>';
        $this->content->text = $output;

        // Set footer
        $this->content->footer = '';


    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_readabilityscore');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return array(
        );
    }
}