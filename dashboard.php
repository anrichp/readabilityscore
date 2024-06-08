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

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/blocks/readabilityscore/lib.php');

$PAGE->set_url('/blocks/readabilityscore/dashboard.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'block_readabilityscore'));
$PAGE->set_heading(get_string('pluginname', 'block_readabilityscore'));

echo $OUTPUT->header();

// Display the table describing the levels of readability difficulty
$table = new html_table();
$table->head = array('Score Range', 'Difficulty Level', 'Notes');
$table->data = array(
    array('90-100', 'Very Easy', 'Easily understood by an average 11-year-old student'),
    array('80-89', 'Easy', 'Conversational English for consumers'),
    array('70-79', 'Fairly Easy', 'Easily understood by 13- to 15-year-old students'),
    array('60-69', 'Standard', 'Easily understood by 15- to 17-year-old students'),
    array('50-59', 'Fairly Difficult', 'Fairly difficult to read'),
    array('30-49', 'Difficult', 'College level and beyond'),
    array('0-29', 'Very Confusing', 'Best understood by university graduates'),
);

echo html_writer::tag('h2', 'Readability Difficulty Levels');
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
    $form = html_writer::tag('form', 
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
                echo html_writer::tag('p', 'Readability Score: ' . $scan->score);
                echo html_writer::tag('p', 'Selected Text: ' . $scan->selectedtext); // Display selected text
                echo html_writer::tag('p', 'Page URL: ' . $scan->pageurl); // Display page URL
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
?>
