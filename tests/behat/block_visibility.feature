@block @block_readabilityscore
Feature: Readability Score Block Visibility and Access
    In order to use the readability score functionality
    As a teacher
    I need to be able to see or not see the block based on my role

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

    Scenario: Block is visible for teachers
        Given I log in as "teacher1"
        And I am on "Course 1" course homepage
        And I turn editing mode on
        When I add the "Readability Score" block
        Then I should see "Readability Score" in the "block_readabilityscore" "block"