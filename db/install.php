<?php

/**
 * Install script for the readabilityscore block.
 *
 * @package block_readabilityscore
 * @copyright 2024 Anrich Potgieter
 * @license https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function xmldb_block_readabilityscore_install()
{
    global $DB;

    // Define table readability_scores to be created
    $table = new xmldb_table('readability_scores');

    // Adding fields to table readability_scores
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
    $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
    $table->add_field('score', XMLDB_TYPE_FLOAT, '10, 5', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('selectedtext', XMLDB_TYPE_TEXT, null, null, null);
    $table->add_field('pageurl', XMLDB_TYPE_TEXT, null, null, null);
    $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

    // Adding keys to table readability_scores
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Conditionally launch create table for readability_scores
    if (!$DB->get_manager()->table_exists($table)) {
        $DB->get_manager()->create_table($table);
    }

    // Define table readability_performance to be created
    $table = new xmldb_table('readability_performance');

    // Adding fields to table readability_performance
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
    $table->add_field('textlength', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
    $table->add_field('executiontime', XMLDB_TYPE_FLOAT, '10, 6', null, XMLDB_NOTNULL);
    $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

    // Adding keys to table readability_performance
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Conditionally launch create table for readability_performance
    if (!$DB->get_manager()->table_exists($table)) {
        $DB->get_manager()->create_table($table);
    }
}