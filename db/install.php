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
 * This function creates the necessary database table for storing readability scores.
 */
function xmldb_block_readabilityscore_install() {
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

    // Create the table if it doesn't already exist
    if (!$DB->get_manager()->table_exists($table)) {
        $DB->get_manager()->create_table($table);
    }
}