<?php
// This file is part of Moodle - (link unavailable)
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
// along with Moodle.  If not, see <(link unavailable)>.

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/blocks/readabilityscore/lib.php');

$PAGE->set_url('/blocks/readabilityscore/dashboard.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'block_readabilityscore'));
$PAGE->set_heading(get_string('pluginname', 'block_readabilityscore'));

echo $OUTPUT->header();

// Fetch all unique page URLs from the database
$pageURLs = $DB->get_fieldset_select('readability_scores', 'DISTINCT pageurl', null, null, 'pageurl');

if (!empty($pageURLs)) {
    // Display form with dropdown to select page URL
    echo '<form method="get">';
    echo '<label for="page-url-select">Select Page URL:</label>';
    echo '<select id="page-url-select" name="page_url">';
    foreach ($pageURLs as $url) {
        echo '<option value="' . $url . '">' . $url . '</option>';
    }
    echo '</select>';
    echo '<button type="submit">View Scans</button>';
    echo '</form>';

    // Check if a specific page URL is selected
    $selectedPageURL = optional_param('page_url', '', PARAM_TEXT);
    if (!empty($selectedPageURL)) {
        // Fetch scans for the selected page URL
        $scans = $DB->get_records_select('readability_scores', 'pageurl = ?', array($selectedPageURL));

        // Display the scans
        if (!empty($scans)) {
            foreach ($scans as $scan) {
                echo '<h2>User ID: ' . $scan->userid . '</h2>';
                echo '<p>Readability Score: ' . $scan->score . '</p>';
                echo '<p>Selected Text: ' . $scan->selectedtext . '</p>'; // Display selected text
                echo '<p>Page URL: ' . $scan->pageurl . '</p>'; // Display page URL
                echo '<p>Time Created: ' . date('Y-m-d H:i:s', $scan->timecreated) . '</p>';
                echo '<hr>';
            }
        } else {
            echo '<p>No scans found for the selected page URL.</p>';
        }
    } else {
        echo '<p>Please select a page URL to view scans.</p>';
    }
} else {
    echo '<p>No scans found.</p>';
}

echo $OUTPUT->footer();
?>
