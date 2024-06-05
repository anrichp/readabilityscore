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

function readability_score($text) {
    // This function should calculate and return the readability score for the given text
    return calculate_readability_score($text);
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function get_readability_score_pages() {
    global $DB;

    $pages = array();

    // Retrieve list of pages from database
    $results = $DB->get_records('readabilityscore_pages');

    foreach ($results as $result) {
        $page = new stdClass();
        $page->title = $result->title;
        $page->score = $result->score;
        $page->text = $result->text;
        $pages[] = $page;
    }

    return $pages;
}

function calculate_readability_score($text) {
    // Placeholder for readability calculation logic
    // Replace this with actual logic to calculate readability

    // Example readability calculation (e.g., Flesch-Kincaid, Gunning Fog, etc.)
    // For now, returning a random score for demonstration purposes
    return rand(0, 100);
}

function store_readability_score($userid, $score, $selectedtext, $pageurl) {
    global $DB;

    // Create a new stdClass object to represent the record
    $record = new stdClass();

    // Assign values to the fields
    $record->userid = $userid;
    $record->score = $score;
    $record->selectedtext = $selectedtext;
    $record->pageurl = $pageurl;
    $record->timecreated = time(); // Current timestamp

    // Insert the record into the 'readability_scores' table
    $DB->insert_record('readability_scores', $record);
}
