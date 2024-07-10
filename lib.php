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

class TextStatistics {
    // ... (existing TextStatistics class code remains unchanged)
}

function preprocess_text($text) {
    $text = strip_tags($text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

function readability_score($text) {
    $textstat = new TextStatistics();
    $preprocessed_text = preprocess_text($text);
    return round($textstat->gunning_fog($preprocessed_text), 2);
}

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

function store_performance_data($textlength, $executiontime) {
    global $DB;
    $record = new stdClass();
    $record->textlength = $textlength;
    $record->executiontime = $executiontime;
    $record->timecreated = time();
    $DB->insert_record('readability_performance', $record);
}