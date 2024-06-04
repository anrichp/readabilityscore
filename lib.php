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
    return $text;
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
    return rand(0, 100); // Example: returning a random score for demonstration
}

function store_readability_score($userid, $score) {
    global $DB;

    // Example table structure
    $record = new stdClass();
    $record->userid = $userid;
    $record->score = $score;
    $record->timecreated = time();

    // Insert the record into your custom table
    $DB->insert_record('readability_scores', $record);
}