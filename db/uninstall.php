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
 * Uninstallation script for the readabilityscore block.
 *
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Custom uninstallation procedure for the readabilityscore block.
 *
 * This function removes the database tables created by the block.
 *
 * @return bool True if uninstallation is successful, false otherwise.
 */
function xmldb_block_readabilityscore_uninstall() {
    global $DB;

    // Define the tables to be dropped
    $tables = ['readabilityscore_pages', 'readability_scores'];

    // Drop each table if it exists
    foreach ($tables as $table) {
        $table = new xmldb_table($table);
        if ($DB->get_manager()->table_exists($table)) {
            $DB->get_manager()->drop_table($table);
        }
    }

    return true;
}