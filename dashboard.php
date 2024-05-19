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

// Display a list of pages with readability scores
$pages = get_readability_score_pages();
foreach ($pages as $page) {
    echo '<h2>' . $page->title . '</h2>';
    echo '<p>Readability Score: ' . $page->score . '</p>';
    echo '<p>' . $page->text . '</p>';
    echo '<hr>';
}

echo $OUTPUT->footer();