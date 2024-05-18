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
    // Split the text into sentences and words
    $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

    // Calculate the average sentence length
    $avg_sentence_length = count($words) / count($sentences);

    // Calculate the average number of syllables per word
    $total_syllables = 0;
    foreach ($words as $word) {
        $total_syllables += count(vowels($word));
    }
    $avg_syllables_per_word = $total_syllables / count($words);

    // Calculate the Flesch-Kincaid Grade Level
    $grade_level = 206.835 - 1.015 * $avg_sentence_length - 84.6 * $avg_syllables_per_word;

    // Round the grade level to one decimal place
    $grade_level = round($grade_level, 1);

    // Return the readability score as a string
    return 'Grade ' . $grade_level;
}

// Helper function to count the number of vowels in a word
function vowels($word) {
    $vowels = array('a', 'e', 'i', 'o', 'u');
    $count = 0;
    foreach (str_split($word) as $char) {
        if (in_array(strtolower($char), $vowels)) {
            $count++;
        }
    }
    return array($count);
}