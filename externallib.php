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

        // Parameter validation
        $params = self::validate_parameters(self::process_text_parameters(), 
                                            array('selectedtext' => $selectedtext, 'pageurl' => $pageurl));

        // Debug readability score calculation
        $debug_info = debug_readability_score($params['selectedtext']);

        // Store the readability score in the database
        store_readability_score($USER->id, $debug_info['gunning_fog_index'], $params['selectedtext'], $params['pageurl']);

        // Return the result with debug info
        return array(
            'readabilityscore' => $debug_info['gunning_fog_index'],
            'debug_info' => json_encode($debug_info)
        );
    }

    public static function process_text_returns()
    {
        return new external_single_structure(
            array(
                'readabilityscore' => new external_value(PARAM_FLOAT, 'The calculated readability score'),
                'debug_info' => new external_value(PARAM_RAW, 'Debug information')
            )
        );
    }
}