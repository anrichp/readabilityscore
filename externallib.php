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

/**
 * External API for the readabilityscore block.
 *
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");
require_once($CFG->dirroot . '/blocks/readabilityscore/lib.php');

class block_readabilityscore_external extends external_api
{
    /**
     * Returns description of process_text parameters.
     *
     * @return external_function_parameters
     */
    public static function process_text_parameters()
    {
        return new external_function_parameters(
            array(
                'selectedtext' => new external_value(PARAM_TEXT, 'The selected text to be processed'),
                'pageurl' => new external_value(PARAM_TEXT, 'The URL of the page where the text originated from')
            )
        );
    }

    /**
     * Process the selected text and calculate readability score.
     *
     * @param string $selectedtext The selected text to be processed
     * @param string $pageurl The URL of the page where the text originated from
     * @return array The processed result including readability score and remediation suggestions
     */
    public static function process_text($selectedtext, $pageurl)
    {
        global $USER, $DB;

        $params = self::validate_parameters(self::process_text_parameters(), 
                                            array('selectedtext' => $selectedtext, 'pageurl' => $pageurl));

        // Use built in php functions to measure execution time and memory usage
        $start_time = microtime(true);
        $start_memory = memory_get_usage();

        $debug_info = debug_readability_score($params['selectedtext']);
        $score = $debug_info['gunning_fog_index'];

        // Store readability score in the database
        store_readability_score($USER->id, $score, $params['selectedtext'], $params['pageurl']);

        $remediationSuggestions = self::generate_remediation_suggestions($score, $debug_info);

        // Call microtime and mermory management to get execution times after readability score calculation
        $end_time = microtime(true);
        $end_memory = memory_get_usage();

        // Calculate execution performance
        $execution_time = $end_time - $start_time;
        $memory_used = $end_memory - $start_memory;
        $text_length = strlen($params['selectedtext']);

        // Log the performance data
        log_performance_data($execution_time, $memory_used, $text_length);

        // Return readability score and remediation suggestions which is then displayed in the DOM
        return array(
            'readabilityscore' => $score,
            'debug_info' => json_encode($debug_info),
            'remediationSuggestions' => $remediationSuggestions
        );
    }

    /**
     * Returns description of process_text return values.
     *
     * @return external_single_structure
     */
    public static function process_text_returns()
    {
        return new external_single_structure(
            array(
                'readabilityscore' => new external_value(PARAM_FLOAT, 'The calculated readability score'),
                'debug_info' => new external_value(PARAM_RAW, 'Debug information'),
                'remediationSuggestions' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, 'Remediation suggestion'),
                    'List of remediation suggestions',
                    VALUE_OPTIONAL
                )
            )
        );
    }

    /**
     * Generate remediation suggestions based on the Gunning Fog Index score and debug info.
     *
     * @param float $score The calculated Gunning Fog Index score
     * @param array $debug_info Debug information containing text statistics
     * @return array An array of remediation suggestions
     */
    private static function generate_remediation_suggestions($score, $debug_info)
    {
        $suggestions = array();

        if ($score > 16) {
            $suggestions[] = "The text is at a graduate level. Consider simplifying the language to make it more accessible to a broader audience.";
            $suggestions[] = "Try to reduce the number of complex words (words with 3 or more syllables) where possible.";
        } elseif ($score > 12) {
            $suggestions[] = "The text is at a college level. If your target audience includes high school students or the general public, consider simplifying some terms.";
        }

        if ($debug_info['sentence_count'] > 0) {
            $avg_sentence_length = $debug_info['word_count'] / $debug_info['sentence_count'];
            if ($avg_sentence_length > 20) {
                $suggestions[] = "Your sentences are quite long (average of " . round($avg_sentence_length, 1) . " words). Try to break up some longer sentences to improve readability.";
            }
        }

        $complex_word_percentage = ($debug_info['complex_word_count'] / $debug_info['word_count']) * 100;
        if ($complex_word_percentage > 10) {
            $suggestions[] = round($complex_word_percentage, 1) . "% of your words are complex (3+ syllables). Consider using simpler alternatives where appropriate.";
        }

        if (empty($suggestions)) {
            $suggestions[] = "Your text has good readability (Gunning Fog Index: " . round($score, 2) . "). Keep up the good work!";
        } else {
            array_unshift($suggestions, "Your text has a Gunning Fog Index of " . round($score, 2) . ". Here are some suggestions to improve readability:");
        }

        return $suggestions;
    }
}