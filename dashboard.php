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
 * Dashboard page for the readabilityscore block.
 *
 * This page displays a summary of readability scores using the Gunning Fog Index
 * and allows users to view detailed scan results.
 *
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/blocks/readabilityscore/lib.php');

// Set up the page
$PAGE->set_url('/blocks/readabilityscore/dashboard.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'block_readabilityscore'));
$PAGE->set_heading(get_string('pluginname', 'block_readabilityscore'));

echo $OUTPUT->header();

// Display the table describing the Gunning Fog Index levels
$table = new html_table();
$table->head = array('Gunning Fog Index', 'Reading Level', 'Notes');
$table->data = array(
    array('6', 'Easy', 'Readable by a 6th grader'),
    array('7', 'Fairly Easy', 'Readable by a 7th grader'),
    array('8', 'Standard', 'Readable by an 8th grader'),
    array('9', 'Fairly Difficult', 'Readable by a high school freshman'),
    array('10', 'Difficult', 'Readable by a high school sophomore'),
    array('11', 'Difficult', 'Readable by a high school junior'),
    array('12', 'Very Difficult', 'Readable by a high school senior'),
    array('13-16', 'College Level', 'Readable by college students'),
    array('17+', 'Graduate Level', 'Readable by college graduates'),
);

echo html_writer::tag('h2', 'Gunning Fog Index - Readability Levels');
echo html_writer::table($table);

// Fetch all unique page URLs from the database
$pageURLs = $DB->get_fieldset_select('readability_scores', 'DISTINCT pageurl', null, null, 'pageurl');

if (!empty($pageURLs)) {
    // Display form with dropdown to select page URL
    $options = array();
    foreach ($pageURLs as $url) {
        $options[$url] = $url;
    }
    $select = html_writer::select($options, 'page_url', '', array('' => 'Select a page'), array('id' => 'page-url-select'));
    $form = html_writer::tag(
        'form',
        html_writer::tag('label', 'Select Page URL:', array('for' => 'page-url-select')) . $select . html_writer::empty_tag('input', array('type' => 'submit', 'value' => 'View Scans')),
        array('method' => 'get')
    );

    echo $form;

    // Check if a specific page URL is selected
    $selectedPageURL = optional_param('page_url', '', PARAM_TEXT);
    if (!empty($selectedPageURL)) {
        // Fetch scans for the selected page URL
        $scans = $DB->get_records_select('readability_scores', 'pageurl = ?', array($selectedPageURL));

        // Display the scans
        if (!empty($scans)) {
            foreach ($scans as $scan) {
                echo html_writer::tag('h2', 'User ID: ' . $scan->userid);
                echo html_writer::tag('p', 'Gunning Fog Index: ' . number_format($scan->score, 2));
                echo html_writer::tag('p', 'Selected Text: ' . $scan->selectedtext);
                echo html_writer::tag('p', 'Page URL: ' . $scan->pageurl);
                echo html_writer::tag('p', 'Time Created: ' . date('Y-m-d H:i:s', $scan->timecreated));
                echo html_writer::empty_tag('hr');
            }
        } else {
            echo html_writer::tag('p', 'No scans found for the selected page URL.');
        }
    } else {
        echo html_writer::tag('p', 'Please select a page URL to view scans.');
    }
} else {
    echo html_writer::tag('p', 'No scans found.');
}

echo $OUTPUT->footer();