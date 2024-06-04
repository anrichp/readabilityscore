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

// Fetch all the scans from the database
$scans = $DB->get_records('readability_scores');

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
    echo '<p>No scans found.</p>';
}

echo $OUTPUT->footer();
?>