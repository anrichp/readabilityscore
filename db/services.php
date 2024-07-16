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
 * External service definitions for the readabilityscore block.
 *
 * This file defines the external functions and services used by the readabilityscore block.
 * It allows the block to interact with Moodle's web services system.
 *
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Definition of external functions
$functions = array(
    'block_readabilityscore_process_text' => array(
        'classname'   => 'block_readabilityscore_external',  // The class containing the external function
        'methodname'  => 'process_text',  // The method to be called
        'classpath'   => 'blocks/readabilityscore/externallib.php',  // The location of the class file
        'description' => 'Process selected text, calculate readability score, and store it in the database.',  // Description of what the function does
        'type'        => 'write',  // This function writes data
        'ajax'        => true,  // This function can be called via AJAX
        'loginrequired' => true,  // User must be logged in to use this function
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE),  // Make this function available for the mobile app
    ),
);

// Definition of services
$services = array(
    'readabilityscore_service' => array(
        'functions' => array('block_readabilityscore_process_text'),  // List of functions this service can call
        'restrictedusers' => 0,  // 0 means the service is not restricted
        'enabled' => 1,  // 1 means the service is enabled
    ),
);