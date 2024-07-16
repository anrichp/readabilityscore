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

/**
 * Library of functions for the readabilityscore block.
 *
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class to calculate various text statistics.
 */
class TextStatistics {
    /**
     * Count the number of words in a text.
     *
     * @param string $text The input text
     * @return int The number of words
     */
    public function word_count($text) {
        return str_word_count($text);
    }

    /**
     * Count the number of sentences in a text.
     *
     * @param string $text The input text
     * @return int The number of sentences
     */
    public function sentence_count($text) {
        return max(1, preg_match_all('/[.!?]+/', $text));
    }

    /**
     * Count the number of complex words in a text.
     *
     * @param string $text The input text
     * @return int The number of complex words
     */
    public function complex_word_count($text) {
        $words = str_word_count($text, 1);
        $complex_words = 0;
        foreach ($words as $word) {
            if ($this->is_complex_word($word)) {
                $complex_words++;
            }
        }
        return $complex_words;
    }

    /**
     * Determine if a word is complex (has more than 2 syllables).
     *
     * @param string $word The word to check
     * @return bool True if the word is complex, false otherwise
     */
    private function is_complex_word($word) {
        // Remove common suffixes
        $word = preg_replace('/(es|ed|ing)$/', '', $word);
        
        // Don't count proper nouns or compound words
        if (ctype_upper($word[0]) || strpos($word, '-') !== false) {
            return false;
        }
        
        return $this->syllable_count($word) > 2;
    }

    /**
     * Count the number of syllables in a word.
     *
     * @param string $word The word to count syllables for
     * @return int The number of syllables
     */
    private function syllable_count($word) {
        $word = strtolower($word);
        $word = preg_replace('/(?:[^laeiouy]es|ed|[^laeiouy]e)$/', '', $word);
        $word = preg_replace('/^y/', '', $word);
        $count = preg_match_all('/[aeiouy]{1,2}/', $word);
        return max(1, $count);
    }

    /**
     * Calculate the Gunning Fog Index for a given text.
     *
     * @param string $text The input text
     * @return float The Gunning Fog Index
     */
    public function gunning_fog($text) {
        $words = $this->word_count($text);
        $sentences = $this->sentence_count($text);
        $complex_words = $this->complex_word_count($text);
        
        $avg_sentence_length = $words / $sentences;
        $percent_complex_words = 100 * ($complex_words / $words);
        
        return 0.4 * ($avg_sentence_length + $percent_complex_words);
    }
}

/**
 * Preprocess the input text by removing HTML tags and extra whitespace.
 *
 * @param string $text The input text
 * @return string The preprocessed text
 */
function preprocess_text($text) {
    $text = strip_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

/**
 * Calculate the readability score for a given text.
 *
 * @param string $text The input text
 * @return float The readability score
 */
function readability_score($text) {
    $textstat = new TextStatistics();
    $preprocessed_text = preprocess_text($text);
    return round($textstat->gunning_fog($preprocessed_text), 2);
}

/**
 * Calculate the readability score and return debug information.
 *
 * @param string $text The input text
 * @return array An array containing debug information and the readability score
 */
function debug_readability_score($text) {
    $textstat = new TextStatistics();
    $preprocessed_text = preprocess_text($text);
    $words = $textstat->word_count($preprocessed_text);
    $sentences = $textstat->sentence_count($preprocessed_text);
    $complex_words = $textstat->complex_word_count($preprocessed_text);
    $fog_index = $textstat->gunning_fog($preprocessed_text);
    
    return [
        'preprocessed_text' => $preprocessed_text,
        'word_count' => $words,
        'sentence_count' => $sentences,
        'complex_word_count' => $complex_words,
        'gunning_fog_index' => round($fog_index, 2)
    ];
}

/**
 * Store the readability score in the database.
 *
 * @param int $userid The ID of the user
 * @param float $score The readability score
 * @param string $selectedtext The selected text
 * @param string $pageurl The URL of the page
 */
function store_readability_score($userid, $score, $selectedtext, $pageurl) {
    global $DB;
    $record = new stdClass();
    $record->userid = $userid;
    $record->score = (float)$score;
    $record->selectedtext = $selectedtext;
    $record->pageurl = $pageurl;
    $record->timecreated = time();
    $DB->insert_record('readability_scores', $record);
}