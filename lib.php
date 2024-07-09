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
    public function remove_punctuation($text) {
        return preg_replace('/[^\w\s]/', '', $text);
    }

    public function lexicon_count($text, $removepunct = true) {
        if ($removepunct) {
            $text = $this->remove_punctuation($text);
        }
        return count(preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY));
    }

    public function sentence_count($text) {
        // Split the text into sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Filter out sentences that are likely to be abbreviations or initials
        $sentences = array_filter($sentences, function($sentence) {
            return strlen($sentence) > 2 && !preg_match('/^[A-Z]\.$/', trim($sentence));
        });
        
        return max(1, count($sentences));
    }

    public function syllable_count($word) {
        $word = strtolower($word);
        $word = $this->remove_punctuation($word);
        
        if (strlen($word) <= 3) {
            return 1;
        }
        
        $word = preg_replace('/(?:[^laeiouy]es|ed|[^laeiouy]e)$/', '', $word);
        $word = preg_replace('/^y/', '', $word);
        
        $syllables = preg_match_all('/[aeiouy]{1,2}/', $word);
        return max(1, $syllables);
    }

    public function difficult_words($text) {
        $words = preg_split('/\s+/', $this->remove_punctuation($text));
        $diff_words_count = 0;
        foreach ($words as $word) {
            if ($this->syllable_count($word) >= 3 && !$this->is_proper_noun($word)) {
                $diff_words_count++;
            }
        }
        return $diff_words_count;
    }
    
    private function is_proper_noun($word) {
        return ctype_upper($word[0]);
    }

    public function gunning_fog($text) {
        $words = $this->lexicon_count($text);
        $sentences = $this->sentence_count($text);
        $difficult_words = $this->difficult_words($text);
        
        $avg_sentence_length = $words / $sentences;
        $percent_difficult_words = ($difficult_words / $words) * 100;
        
        $fog_index = 0.4 * ($avg_sentence_length + $percent_difficult_words);
        
        return round($fog_index, 2);
    }
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
    return $textstat->gunning_fog($preprocessed_text);
}

function debug_readability_score($text) {
    $textstat = new TextStatistics();
    $preprocessed_text = preprocess_text($text);
    $words = $textstat->lexicon_count($preprocessed_text);
    $sentences = $textstat->sentence_count($preprocessed_text);
    $difficult_words = $textstat->difficult_words($preprocessed_text);
    $fog_index = $textstat->gunning_fog($preprocessed_text);
    
    return [
        'preprocessed_text' => $preprocessed_text,
        'word_count' => $words,
        'sentence_count' => $sentences,
        'difficult_word_count' => $difficult_words,
        'gunning_fog_index' => $fog_index
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