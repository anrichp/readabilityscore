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
    private $round_outputs = true;

    public function set_round_outputs($round) {
        $this->round_outputs = $round;
    }

    private function legacy_round($number, $precision = 2) {
        if ($this->round_outputs) {
            return round($number, $precision);
        }
        return $number;
    }

    public function lexicon_count($text) {
        $text = preg_replace('/[^a-zA-Z\s]/', '', $text);
        return count(preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY));
    }

    public function sentence_count($text) {
        return max(1, preg_match_all('/[.!?]+/', $text));
    }

    public function avg_sentence_length($text) {
        $words = $this->lexicon_count($text);
        $sentences = $this->sentence_count($text);
        return $words / $sentences;
    }

    public function syllable_count($word) {
        $word = strtolower($word);
        $word = preg_replace('/[^a-z]/', '', $word);
        
        if (strlen($word) <= 3) {
            return 1;
        }
        
        $word = preg_replace('/(?:[^laeiouy]es|ed|[^laeiouy]e)$/', '', $word);
        $word = preg_replace('/^y/', '', $word);
        
        $syllables = preg_match_all('/[aeiouy]{1,2}/', $word);
        return max(1, $syllables);
    }

    public function polysyllabcount($text) {
        $words = preg_split('/\s+/', $text);
        $polysyllable_count = 0;
        foreach ($words as $word) {
            if ($this->syllable_count($word) >= 3) {
                $polysyllable_count++;
            }
        }
        return $polysyllable_count;
    }

    public function gunning_fog($text) {
        $words = $this->lexicon_count($text);
        $sentences = $this->sentence_count($text);
        $polysyllables = $this->polysyllabcount($text);
        
        $fog_index = 0.4 * (($words / $sentences) + (100 * ($polysyllables / $words)));
        
        return $this->legacy_round($fog_index, 2);
    }
}

function readability_score($text) {
    $textstat = new TextStatistics();
    return $textstat->gunning_fog($text);
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