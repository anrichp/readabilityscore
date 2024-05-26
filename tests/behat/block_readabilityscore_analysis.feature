Feature: Readability Analysis
  In order to analyse readability
  As a teacher
  I can use the readability analysis block

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

Scenario: Successful readability analysis
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  And I fill in the content input field with "Lorem ipsum dolor sit amet"
  And I press the "Analyze" button
  Then I should see the readability analysis results displayed in the block

Scenario: Unsuccessful readability analysis (empty input)
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  And I press the "Analyze" button
  Then I should see an error message displayed in the block

Scenario: Unsuccessful readability analysis (invalid input)
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  And I fill in the content input field with "<invalid input>"
  And I press the "Analyze" button
  Then I should see an error message displayed in the block