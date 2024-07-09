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
    private $lang = "en_US";
    private $round_outputs = true;
    private $round_points = null;
    private $rm_apostrophe = true;

    public function readability_score($text) {
        return $this->gunning_fog($text);
    }

    public function gunning_fog($text) {
        $syllable_threshold = $this->get_lang_cfg("syllable_threshold", 3);
        try {
            $per_diff_words = ($this->difficult_words($text, $syllable_threshold) / $this->lexicon_count($text)) * 100;
            $grade = 0.4 * ($this->avg_sentence_length($text) + $per_diff_words);
            return $this->legacy_round($grade, 2);
        } catch (Exception $e) {
            return 0.0;
        }
    }

    private function get_lang_cfg($key, $default) {
        return $default;
    }

    private function difficult_words($text, $syllable_threshold) {
        $words = $this->remove_punctuation($text);
        $words = preg_split('/\s+/', $words, -1, PREG_SPLIT_NO_EMPTY);
        $diff_words_count = 0;
        foreach ($words as $word) {
            if ($this->syllable_count($word) >= $syllable_threshold) {
                $diff_words_count++;
            }
        }
        return $diff_words_count;
    }

    private function remove_punctuation($text) {
        if ($this->rm_apostrophe) {
            $pattern = '/[^\p{L}\p{N}\s]/u';
        } else {
            $text = preg_replace("/\'(?![tsd]\b|ve\b|ll\b|re\b)/u", '"', $text);
            $pattern = '/[^\p{L}\p{N}\s\']/u';
        }
        return preg_replace($pattern, '', $text);
    }

    private function syllable_count($word) {
        $word = mb_strtolower($word, 'UTF-8');
        $word = preg_replace('/[^a-z]/u', '', $word);
        if (mb_strlen($word) <= 3) {
            return 1;
        }
        $word = preg_replace('/(?:[^laeiouy]es|ed|[^laeiouy]e)$/', '', $word);
        $word = preg_replace('/^y/', '', $word);
        $matches = preg_match_all('/[aeiouy]{1,2}/', $word, $parts);
        return max(1, $matches);
    }

    private function lexicon_count($text, $removepunct = true) {
        if ($removepunct) {
            $text = $this->remove_punctuation($text);
        }
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        return count($words);
    }

    private function sentence_count($text) {
        $sentences = preg_split('/(?<=[.!?])\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        return max(1, count($sentences));
    }

    private function avg_sentence_length($text) {
        $asl = $this->lexicon_count($text) / $this->sentence_count($text);
        return $this->legacy_round($asl, 1);
    }

    private function legacy_round($number, $precision = 0) {
        $precision = $this->round_points !== null ? $this->round_points : $precision;
        if ($this->round_outputs) {
            $factor = pow(10, $precision);
            return floor(($number * $factor) + 0.5) / $factor;
        } else {
            return $number;
        }
    }
}

function readability_score($text) {
    $textstat = new TextStatistics();
    return $textstat->readability_score($text);
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