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
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'block_readabilityscore_process_text' => array(
        'classname'   => 'block_readabilityscore_external',
        'methodname'  => 'process_text',
        'classpath'   => 'blocks/readabilityscore/externallib.php',
        'description' => 'Process selected text, calculate readability score, and store it in the database.',
        'type'        => 'write',
        'ajax'        => true,
        'loginrequired' => true,
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE), // Ensure it’s available for mobile
    ),
);

$services = array(
    'readabilityscore_service' => array(
        'functions' => array('block_readabilityscore_process_text'),
        'restrictedusers' => 0,
        'enabled' => 1,
    ),
);
