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
     * Simulates text selection and triggers the analysis.
     *
     * @When /^I simulate text selection with "([^"]*)"$/
     * @param string $text The text to be selected and analyzed
     */
    public function i_simulate_text_selection_with($text)
    {
        $session = $this->getSession();

        // Create a temporary element with the text
        $script = "
            var tempElement = document.createElement('div');
            tempElement.textContent = " . json_encode($text) . ";
            document.body.appendChild(tempElement);
            
            var range = document.createRange();
            range.selectNodeContents(tempElement);
            
            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            
            // Trigger the mouseup event to start the analysis
            var event = new MouseEvent('mouseup', {
                'view': window,
                'bubbles': true,
                'cancelable': true
            });
            tempElement.dispatchEvent(event);
            
            // Clean up
            document.body.removeChild(tempElement);
        ";

        $session->executeScript($script);

        // Wait for the analysis to complete (adjust the timeout as needed)
        $this->getSession()->wait(5000, "document.querySelector('#readability-result').textContent.includes('Readability Score:')");
    }

    /**
     * Checks for a number followed by specific text in a given element.
     *
     * @Then /^I should see a number followed by "([^"]*)" in the "([^"]*)" "([^"]*)"$/
     * @param string $text The text to look for after the number
     * @param string $element The element to search in
     * @param string $selectorType The type of selector (e.g., 'css_element', 'xpath')
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
