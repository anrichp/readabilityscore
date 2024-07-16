<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     block_readabilityscore
 * @copyright   2024 Anrich Potgieter <anrichp@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// The name of the component. It always starts with 'block_' for blocks.
$plugin->component = 'block_readabilityscore';

// The release version of the plugin.
$plugin->release = '0.2.5';

// The version number of the plugin. This should be increased on each change.
$plugin->version = 2024070802;

// The minimum Moodle version required for this plugin to function.
$plugin->requires = 2022112800;

// The maturity level of the plugin. MATURITY_ALPHA, MATURITY_BETA, MATURITY_RC, MATURITY_STABLE.
$plugin->maturity = MATURITY_ALPHA;

// The location of the uninstall script for the plugin.
$plugin->uninstall = 'blocks/readabilityscore/db/uninstall.php';