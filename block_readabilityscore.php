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

/**
 * Readability Score block class.
 */
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
            'mod' => true,
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
        if (!isloggedin() || isguestuser()) {
            // Only real users can access this block.
            return;
        }

        // Instantiate global variables
        global $PAGE, $OUTPUT;

        // Load the CSS file for text selection element
        $PAGE->requires->css('/blocks/readabilityscore/styles/readabilityscore.css');

        // Initialise the text property
        $this->content = new stdClass();
        $this->content->text = '';

        // Add button to open dashboard page
        $dashboardButton = new single_button(
            new moodle_url('/blocks/readabilityscore/dashboard.php'), // URL for the dashboard
            'Dashboard', // Button text
            'get', // Method
            single_button::BUTTON_PRIMARY // Button style
        );

        // Add scan button to trigger JavaScript
        $scanButton = html_writer::tag('button', 'Scan', array('id' => 'scan-button', 'class' => 'btn btn-primary mt-1'));

        // Render the dashboard button
        $this->content->text .= html_writer::tag('div', $OUTPUT->render($dashboardButton));
        // Render the scan button
        $this->content->text .= $scanButton;

        // Add a container for displaying selected text
        $selectedTextContainer = html_writer::tag('h4', 'Readability Result:', array('id' => 'selected-text-container', 'class' => 'pt-1'));
        $this->content->text .= $selectedTextContainer;

        // Add a div to hold the selected text
        $selectedTextContainerDiv = html_writer::tag('div', '', array('id' => 'readability-result'));
        $this->content->text .= $selectedTextContainerDiv;

        // Include the JavaScript file
        $PAGE->requires->js_call_amd('block_readabilityscore/main', 'init');

        return $this->content;
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