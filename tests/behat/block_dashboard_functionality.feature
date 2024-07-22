@block @block_readabilityscore
Feature: Readability Score Dashboard
    In order to view historical readability data
    As a teacher
    I need to access the readability score dashboard

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

    Scenario: Access dashboard and view data
        Given I log in as "teacher1"
        And I am on "Course 1" course homepage
        And I turn editing mode on
        And I add the "Readability Score" block
        When I click on "Dashboard" "button" in the "Readability Score" "block"
        Then I should see "Readability Score Dashboard"
        And I should see "Gunning Fog Index - Readability Levels"