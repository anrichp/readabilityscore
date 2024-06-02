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

class block_readabilityscore extends block_base
{

    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_readabilityscore');
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats()
    {
        return [
            'course-view' => true,
            'site' => true,
            'mod' => false,
            'my' => false,
        ];
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content()
    {

        // Determine whether user is logged in
        if (!isloggedin() or isguestuser()) {
            // Only real users can access myprofile block.
            return;
        }

        // Instantiate global variables
        global $PAGE, $OUTPUT;

        // Get current page content
        // $text = $PAGE->get_renderer('core')->main_content();

        // Send text to readability score function
        // $score = readability_score($stripped_text);

        //Display readability score
        $this->content = new stdClass();
        $this->content->text = 'Readability Score: ';

        // Add button to open dashboard page
        $dashboardButton = new single_button(
            new moodle_url('/blocks/readabilityscore/dashboard.php'), // Replace with the correct URL for the dashboard
            'Dashboard', // Button text
            'get', // Method
            single_button::BUTTON_PRIMARY // Button style
        );

        // Add scan button to trigger javascript
        $scanButton = new single_button(
            new moodle_url('#'),
            'Scan',
            'Post',
            single_button::BUTTON_PRIMARY,
            array('id' => 'scan-button', 'class' => 'block_readabilityscore_scanbutton')
        );

        // Render the dashboard button
        $this->content->text .= html_writer::tag('div', $OUTPUT->render($dashboardButton));
        // Render the button and append it to the content
        $this->content->text .= html_writer::tag('div', $OUTPUT->render($scanButton), ['class' => 'block_readabilityscore_button, pt-1']);

        // Include the JavaScript file
        $jsUrl = new moodle_url('/blocks/readabilityscore/js/readability.js');
        $this->page->requires->js($jsUrl);
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization()
    {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_readabilityscore');
        } else {
            $this->title = $this->config->title;
        }
    }
}
