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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

function readability_score($text)
{
    // This function calculates and returns the Gunning Fog Index for the given text
    return calculate_gunning_fog_index($text);
}

// Helper function to count words in the text
function count_words_custom($text) {
    return str_word_count($text);
}

// Helper function to count sentences in the text
function count_sentences($text) {
    return max(1, preg_match_all('/[.!?]+/', $text));
}

// Helper function to count complex words (words with 3 or more syllables)
function count_complex_words($text) {
    $words = str_word_count($text, 1);
    $complex_words = 0;
    foreach ($words as $word) {
        if (count_syllables($word) >= 3) {
            $complex_words++;
        }
    }
    return $complex_words;
}

// Helper function to count syllables in a word
function count_syllables($word) {
    $word = strtolower($word);
    $word = preg_replace('/[^a-z]/', '', $word);
    if (strlen($word) === 0) {
        return 0;
    }
    $pattern = [
        '/[aeiouy]{1,2}/',
        '/[^aeiouy]/',
        '/(?:[^laeiouy]es|ed|[^laeiouy]e|[aeiouy]e)$/',
        '/^y/'
    ];
    $syllable_count = preg_match_all($pattern[0], $word) - preg_match_all($pattern[2], $word) + preg_match_all($pattern[3], $word);
    return $syllable_count > 0 ? $syllable_count : 1;
}

// Main function to calculate Gunning Fog Index
function calculate_gunning_fog_index($text) {
    $word_count = count_words_custom($text);
    $sentence_count = count_sentences($text);
    $complex_word_count = count_complex_words($text);

    if ($word_count == 0 || $sentence_count == 0) {
        return 0;
    }

    $avg_sentence_length = $word_count / $sentence_count;
    $percent_complex_words = ($complex_word_count / $word_count) * 100;

    $gunning_fog_index = 0.4 * ($avg_sentence_length + $percent_complex_words);

    // Round the Gunning Fog Index to one decimal place
    return round($gunning_fog_index, 1);
}

function store_readability_score($userid, $score, $selectedtext, $pageurl)
{
    global $DB;
    $record = new stdClass();
    $record->userid = $userid;
    $record->score = $score;
    $record->selectedtext = $selectedtext;
    $record->pageurl = $pageurl;
    $record->timecreated = time();
    $DB->insert_record('readability_scores', $record);
}