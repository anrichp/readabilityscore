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
 * Behat step definitions for the Readability Score block.
 *
 * @package    block_readabilityscore
 * @category   test
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Mink\Exception\ExpectationException as ExpectationException;

/**
 * Steps definitions for the Readability Score block.
 *
 * @package    block_readabilityscore
 * @category   test
 * @copyright  2024 Anrich Potgieter
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_block_readabilityscore extends behat_base
{

    /**
     * Confirms the dialogue.
     *
     * @When /^I confirm the dialogue$/
     */
    public function i_confirm_the_dialogue()
    {
        $this->execute('behat_general::i_dismiss_the_popup');
    }

    /**
     * Highlights the specified text on the page and triggers the analysis.
     *
     * @When /^I highlight the text "([^"]*)"$/
     * @param string $text The text to be highlighted
     */
    public function i_highlight_the_text($text)
    {
        $session = $this->getSession();
        $page = $session->getPage();

        // Find an element containing the text
        $element = $page->find('xpath', "//*[contains(text(), '$text')]");
        if (!$element) {
            throw new ExpectationException("Text '$text' not found on the page", $session);
        }

        // Use JavaScript to highlight the text and trigger the analysis
        $script = "
            var range = document.createRange();
            var textNode = arguments[0].firstChild;
            range.setStart(textNode, 0);
            range.setEnd(textNode, textNode.length);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            
            // Trigger the analysis
            var event = new MouseEvent('mouseup', {
                'view': window,
                'bubbles': true,
                'cancelable': true
            });
            arguments[0].dispatchEvent(event);
        ";
        $session->executeScript($script, [$element]);

        // Wait for the analysis to complete (adjust the timeout as needed)
        $this->getSession()->wait(5000, "document.querySelector('#readability-result').textContent.includes('Readability Score:')");
    }

    /**
     * Checks for a number followed by specific text in a given element.
     *
     * @Then /^I should see a number followed by "([^"]*)" in the "([^"]*)" "([^"]*)"$/
     * @param string $text The text to look for after the number
     * @param string $element The element to search in
     * @param string $selectorType The type of selector (e.g., 'css', 'xpath')
     * @throws ExpectationException
     */
    public function i_should_see_a_number_followed_by_in_the($text, $element, $selectorType)
    {
        $container = $this->find($selectorType, $element);
        if (!$container) {
            throw new ExpectationException("The $element element was not found", $this->getSession());
        }

        $content = $container->getText();
        if (!preg_match('/(\d+(\.\d+)?)\s+' . preg_quote($text, '/') . '/', $content)) {
            throw new ExpectationException("Did not find a number followed by '$text' in the content", $this->getSession());
        }
    }
}
