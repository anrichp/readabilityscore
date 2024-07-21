@block @block_readabilityscore @javascript
Feature: Text Analysis Functionality
    In order to assess the readability of course content
    As a teacher
    I need to be able to analyze selected text

    Background:
        Given the following "users" exist:
            | username | firstname | lastname | email                |
            | teacher1 | Teacher   | 1        | teacher1@example.com |
        And the following "courses" exist:
            | fullname | shortname |
            | Course 1 | C1        |
        And the following "course enrolments" exist:
            | user     | course | role           |
            | teacher1 | C1     | editingteacher |

    Scenario: Analyze text and display score
        Given I log in as "teacher1"
        And I am on "Course 1" course homepage
        And I turn editing mode on
        And I add the "Readability Score" block
        When I click on "Scan" "button" in the "Readability Score" "block"
        And I confirm the dialogue
        And I highlight the text "This is a sample text for readability analysis."
        Then I should see "Readability Score:" in the "readability-result" "div"
        And I should see "Difficulty Level:" in the "readability-result" "div"
        And I should see a number followed by "Difficulty Level:" in the "readability-result" "div"

    Scenario: Verify remediation suggestions
        Given I log in as "teacher1"
        And I am on "Course 1" course homepage
        And I turn editing mode on
        And I add the "Readability Score" block
        When I click on "Scan" "button" in the "Readability Score" "block"
        And I confirm the dialogue
        And I highlight the text "The quantum mechanical model of the atom is a complex and sophisticated representation of atomic structure that incorporates principles of wave-particle duality and probabilistic interpretations of electron behavior."
        Then I should see "Readability Score:" in the "readability-result" "div"
        And I should see "Difficulty Level:" in the "readability-result" "div"
        And I should see "Remediation Suggestions:" in the "readability-result" "div"
        And I should see "Consider simplifying some terms" in the "readability-result" "div"