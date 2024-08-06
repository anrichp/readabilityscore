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
 * Installation script for the readabilityscore block.
 *
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Custom installation procedure for the readabilityscore block.
 *
 * This function creates the necessary database tables for storing readability scores
 * and performance data.
 */
function xmldb_block_readabilityscore_install() {
    global $DB;

    // Define table readability_scores to be created
    $table_scores = new xmldb_table('readability_scores');

    // Adding fields to table readability_scores
    $table_scores->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
    $table_scores->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
    $table_scores->add_field('score', XMLDB_TYPE_FLOAT, '10, 5', null, XMLDB_NOTNULL, null, '0');
    $table_scores->add_field('selectedtext', XMLDB_TYPE_TEXT, null, null, null);
    $table_scores->add_field('pageurl', XMLDB_TYPE_TEXT, null, null, null);
    $table_scores->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

    // Adding keys to table readability_scores
    $table_scores->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Create the table if it doesn't already exist
    if (!$DB->get_manager()->table_exists($table_scores)) {
        $DB->get_manager()->create_table($table_scores);
    }

    // Define table block_readabilityscore_performance to be created
    $table_performance = new xmldb_table('block_readabilityscore_performance');

    // Adding fields to table block_readabilityscore_performance
    $table_performance->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
    $table_performance->add_field('execution_time', XMLDB_TYPE_NUMBER, '20, 10', null, XMLDB_NOTNULL);
    $table_performance->add_field('memory_used', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL);
    $table_performance->add_field('text_length', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
    $table_performance->add_field('timestamp', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

    // Adding keys to table block_readabilityscore_performance
    $table_performance->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Create the table if it doesn't already exist
    if (!$DB->get_manager()->table_exists($table_performance)) {
        $DB->get_manager()->create_table($table_performance);
    }
}