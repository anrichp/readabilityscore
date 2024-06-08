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
require_once('SimpleXLSXGen.php'); // Include SimpleXLSXGen.php

$PAGE->set_url('/blocks/readabilityscore/dashboard.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'block_readabilityscore'));
$PAGE->set_heading(get_string('pluginname', 'block_readabilityscore'));

echo $OUTPUT->header();

// Fetch all unique page URLs from the database
$pageURLs = $DB->get_fieldset_select('readability_scores', 'DISTINCT pageurl', null, null, 'pageurl');

if (!empty($pageURLs)) {
    // Display form with dropdown to select page URL
    $urlSelect = html_writer::start_tag('form', array('method' => 'get'));
    $urlSelect .= html_writer::tag('label', 'Select Page URL:', array('for' => 'page-url-select'));
    $urlSelect .= html_writer::start_tag('select', array('id' => 'page-url-select', 'name' => 'page_url'));
    foreach ($pageURLs as $url) {
        $urlSelect .= html_writer::tag('option', $url, array('value' => $url));
    }
    $urlSelect .= html_writer::end_tag('select');
    $urlSelect .= html_writer::tag('button', 'View Scans');
    $urlSelect .= html_writer::end_tag('form');
    echo $urlSelect;

    // Check if export to Excel button is clicked
    if (isset($_GET['export_excel'])) {
        // Fetch scans for the selected page URL
        $selectedPageURL = optional_param('page_url', '', PARAM_TEXT);
        $scans = $DB->get_records_select('readability_scores', 'pageurl = ?', array($selectedPageURL));

        // Create data array for Excel
        $data = [['User ID', 'Readability Score', 'Selected Text', 'Page URL', 'Time Created']];
        foreach ($scans as $scan) {
            $data[] = [
                $scan->userid,
                $scan->score,
                $scan->selectedtext,
                $scan->pageurl,
                date('Y-m-d H:i:s', $scan->timecreated)
            ];
        }

        // Generate and download Excel file
        $xlsx = SimpleXLSXGen::fromArray($data);
        $xlsx->downloadAs('scans.xlsx');
    }

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
                echo html_writer::tag('hr');
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
