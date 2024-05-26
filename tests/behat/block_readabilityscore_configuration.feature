Feature: Block Configuration
  In order to configure the readability analysis block
  As a teacher
  I can set the block's settings

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

Scenario: Successful block configuration
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  And I configure the block with the following settings:
    | setting | value |
    | threshold | 60 |
    | grade | 70 |
  Then the block's settings should be saved successfully

Scenario: Unsuccessful block configuration (invalid input)
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  And I configure the block with the following settings:
    | setting | value |
    | threshold | abc |
    | grade | 70 |
  Then I should see an error message indicating invalid input

Scenario: Unsuccessful block configuration (missing input)
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  And I configure the block with the following settings:
    | setting | value |
    | threshold | 60 |
  Then I should see an error message indicating missing input