<?php
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");
require_once($CFG->dirroot . '/blocks/readabilityscore/lib.php');

class block_readabilityscore_external extends external_api
{
    public static function process_text_parameters()
    {
        return new external_function_parameters(
            array(
                'selectedtext' => new external_value(PARAM_TEXT, 'The selected text to be processed'),
                'pageurl' => new external_value(PARAM_TEXT, 'The URL of the page where the text originated from')
            )
        );
    }

    public static function process_text($selectedtext, $pageurl)
    {
        global $USER, $DB;

        $params = self::validate_parameters(self::process_text_parameters(), 
                                            array('selectedtext' => $selectedtext, 'pageurl' => $pageurl));

        $debug_info = debug_readability_score($params['selectedtext']);
        $score = $debug_info['gunning_fog_index'];

        store_readability_score($USER->id, $score, $params['selectedtext'], $params['pageurl']);

        $remediationSuggestions = self::generate_remediation_suggestions($score, $debug_info);

        return array(
            'readabilityscore' => $score,
            'debug_info' => json_encode($debug_info),
            'remediationSuggestions' => $remediationSuggestions
        );
    }

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