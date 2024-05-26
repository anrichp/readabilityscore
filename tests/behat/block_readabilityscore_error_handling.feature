Feature: Error Handling
  In order to handle errors gracefully
  As a teacher
  I can see error messages when something goes wrong

Background:
  Given the following "users" exist:
    | username | firstname | lastname | email | idnumber |
    | teacher1 | Teacher | 1 | teacher1@example.com | T1 |
  And the following "courses" exist:
    | fullname | shortname | category |
    | Course 1 | C1 | 0 |
  And the following "course enrolments" exist:
    | user | course | role |
    | teacher1 | C1 | editingteacher |

Scenario: Error handling for readability analysis
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  And I fill in the content input field with "Invalid input"
  And I press the "Analyze" button
  Then I should see an error message indicating invalid input

Scenario: Error handling for simplification recommendations
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Simplification Recommendations" block
  And I fill in the content input field with "Invalid input"
  And I press the "Simplify" button
  Then I should see an error message indicating invalid input

Scenario: Error handling for block configuration
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  And I configure the block with the following settings:
    | setting | value |
    | threshold | abc |
  Then I should see an error message indicating invalid input