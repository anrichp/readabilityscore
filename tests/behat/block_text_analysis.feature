@block @block_readabilityscore @javascript0
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
            | Course 1 | course1   |
        And the following "course enrolments" exist:
            | user     | course  | role           |
            | teacher1 | course1 | editingteacher |

    Scenario: Analyze text and display score
        Given I log in as "teacher1"
        And I am on "Course 1" course homepage
        And I turn editing mode on
        And I add the "Readability Score" block
        When I click on "Scan" "button" in the "Readability Score" "block"
        And I select the text "The quick brown fox jumps over the lazy dog."
        Then I should see "Readability Score:" in the "Readability Score" "block"
        And I should see a number in the "Readability Score" "block"