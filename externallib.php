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
 * External library functions for the readabilityscore block.
 *
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");
require_once($CFG->dirroot . '/blocks/readabilityscore/lib.php');

/**
 * External API for the readabilityscore block.
 */
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

        // Validate parameters
        $params = self::validate_parameters(self::process_text_parameters(), 
                                            array('selectedtext' => $selectedtext, 'pageurl' => $pageurl));

        // Calculate readability score and get debug info
        $debug_info = debug_readability_score($params['selectedtext']);
        $score = $debug_info['gunning_fog_index'];

        // Store the readability score in the database
        store_readability_score($USER->id, $score, $params['selectedtext'], $params['pageurl']);

        // Generate remediation suggestions based on the score and debug info
        $remediationSuggestions = self::generate_remediation_suggestions($score, $debug_info);

        // Return the results
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
     * Generate remediation suggestions based on the readability score and debug info.
     *
     * @param float $score The calculated readability score
     * @param array $debug_info Debug information containing text statistics
     * @return array An array of remediation suggestions
     */
    private static function generate_remediation_suggestions($score, $debug_info)
    {
        $suggestions = array();

        if ($score > 12) {
            $suggestions[] = "Consider simplifying your language to improve readability.";
        }

        if ($debug_info['sentence_count'] > 0 && $debug_info['word_count'] / $debug_info['sentence_count'] > 20) {
            $suggestions[] = "Try to shorten your sentences. Aim for an average of 15-20 words per sentence.";
        }

        if ($debug_info['complex_word_count'] / $debug_info['word_count'] > 0.1) {
            $suggestions[] = "Use simpler words where possible. Try to reduce the number of complex words (words with 3 or more syllables).";
        }

        return $suggestions;
    }
}