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
 * Block readabilityscore is defined here.
 *
 * @package     block_readabilityscore
 * @copyright   2024 Anrich Potgieter <anrichp@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/lib.php');

class block_readabilityscore extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_readabilityscore');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {

        // if ($this->content !== null) {
        //     return $this->content;
        // }

        // if (empty($this->instance)) {
        //     $this->content = '';
        //     return $this->content;
        // }

        // $this->content = new stdClass();
        // $this->content->items = array();
        // $this->content->icons = array();
        // $this->content->footer = '';

        // if (!empty($this->config->text)) {
        //     $this->content->text = $this->config->text;
        // } else {
        //     $text = 'Please define the content text in /blocks/readabilityscore/block_readabilityscore.php.';
        //     $this->content->text = $text;
        // }

        // return $this->content;
        global $PAGE;

        // Get current page content
        $text = 'In the crepuscular languor of a forgotten epoch, amidst monolithic edifices sculpted from antediluvian obsidian, resided Erasmus, a man of peregrine proclivities and an intellect that rivaled the most venerated polymaths. His sole companion was Euphemia, a cerulean automaton fashioned from the filigreed craftsmanship of a bygone era. Erasmus, burdened by an insatiable epistemological yearning, toiled incessantly within his sanctum, a labyrinthine library overflowing with crumbling parchments inscribed in arcane tongues.';

        // Send text to readability score function
        $score = readability_score($text);

        //Display readability score
        $this->content = new stdClass();
        $this->content->text = 'Readability Score: ' . $score;
        $this->content->footer = '';


    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_readabilityscore');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return array(
        );
    }
}