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
 * Readabilityscore capabilities definition
 *
 * This file defines the capabilities for the readabilityscore block.
 * It's required to store the block permissions in the database.
 * For more information, see: https://moodledev.io/docs/4.4/apis/subsystems/access
 *
 * @package    block_readabilityscore
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array (
    'block/readabilityscore:addinstance' => array(
        'riskbitmask' => RISK_XSS,  // This capability carries a risk of XSS (Cross-site scripting)
        'captype' => 'write',  // This is a write-type capability
        'contextlevel' => CONTEXT_BLOCK,  // This capability applies at the block context level
        'archetypes' => array (
            'editingteacher' => CAP_ALLOW,  // Editing teachers are allowed to add this block
        ),
    ),
);