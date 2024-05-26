Feature: Block Visibility
  In order to control the visibility of the readability analysis block
  As a teacher
  I can show or hide the block on the course page

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

Scenario: Block is visible on course page
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I add the "Readability Analysis" block
  Then the block should be visible on the course page

Scenario: Block is not visible on course page (not authorized)
  Given I log in as "student1"
  And I am on "Course 1" course homepage
  Then the "Readability Analysis" block should not be visible on the course page

Scenario: Hide block on course page
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I hide the "Readability Analysis" block
  Then the block should not be visible on the course page

Scenario: Show block on course page
  Given I log in as "teacher1"
  And I am on "Course 1" course homepage with editing mode on
  When I show the "Readability Analysis" block
  Then the block should be visible on the course page