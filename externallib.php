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
        $params = self::validate_parameters(self::process_text_parameters(), array('selectedtext' => $selectedtext, 'pageurl' => $pageurl));

        // Readability score calculation (implement this function as needed)
        $readabilityscore = calculate_readability_score($params['selectedtext']);

        // Store the readability score in the database (implement this function as needed)
        store_readability_score($USER->id, $readabilityscore, $params['selectedtext'], $params['pageurl']);

        // Return the result
        return array('readabilityscore' => $readabilityscore);
    }

    public static function process_text_returns()
    {
        return new external_single_structure(
            array(
                'readabilityscore' => new external_value(PARAM_INT, 'The calculated readability score')
            )
        );
    }
}
