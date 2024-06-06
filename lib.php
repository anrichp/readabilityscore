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

function readability_score($text)
{
    // This function should calculate and return the readability score for the given text
    return calculate_readability_score($text);
}

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

// Helper function to count syllables in a word
function count_syllables($word) {
    $word = strtolower($word);
    $word = preg_replace('/[^a-z]/', '', $word);
    if (strlen($word) === 0) {
        return 0;
    }

    // Regex patterns for common syllable patterns
    $pattern = [
        '/[aeiouy]{1,2}/', // vowel groups
        '/[^aeiouy]/',     // non-vowel groups
        '/(?:[^laeiouy]es|ed|[^laeiouy]e|[aeiouy]e)$/', // remove 'es', 'ed', 'e'
        '/^y/'             // leading 'y' counts as a vowel
    ];

    // Count the number of vowel groups in the word
    $syllable_count = preg_match_all($pattern[0], $word) - preg_match_all($pattern[2], $word) + preg_match_all($pattern[3], $word);
    return $syllable_count > 0 ? $syllable_count : 1;
}

// Helper function to count words in the text (rename to avoid conflict)
function count_words_custom($text) {
    $words = str_word_count($text, 1);
    return count($words);
}

// Helper function to count sentences in the text
function count_sentences($text) {
    $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    return count($sentences);
}

// Main function to calculate readability score
function calculate_readability_score($text) {
    // Calculate total words, sentences, and syllables
    $total_words = count_words_custom($text); // Use the renamed function
    $total_sentences = count_sentences($text);
    $total_syllables = 0;
    $words = str_word_count($text, 1);
    foreach ($words as $word) {
        $total_syllables += count_syllables($word);
    }

    // Calculate Flesch Reading Ease Score
    $reading_ease = 206.835 - (1.015 * ($total_words / $total_sentences)) - (84.6 * ($total_syllables / $total_words));

    // Round the readability score to an integer
    $rounded_reading_ease = round($reading_ease);

    return $rounded_reading_ease;
}

function store_readability_score($userid, $score, $selectedtext, $pageurl)
{
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
