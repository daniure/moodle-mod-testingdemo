@mod @mod_testingdemo
Feature: View Testing Demo activities

  Background:
    Given the following "users" exist:
    | username | firstname | lastname | email                |
    | manager  | Manager   | User     | manager@example.com  |
    | teacher  | Teacher   | User     | teacher@example.com  |
    | student  | Student   | User     | student1@example.com |
    And the following "courses" exist:
    | fullname | shortname | category |
    | Course 1 | C1        |        0 |
    And the following "course enrolments" exist:
    | user    | course | role           |
    | teacher | C1     | editingteacher |
    | student | C1     | student        |
    And the following "role assigns" exist:
    | user    | role    | contextlevel | reference |
    | manager | manager | System       |           |
    And I log in as "teacher"
    And I follow "Course 1"
    And I turn editing mode on
    And I add a "Testing demo" to section "1" and I fill the form with:
    | Name        | Testing Demo             |
    | Description | Testing Demo description |
    And I log out

  Scenario: Not enrolled
    Given I log in as "manager"
    And I follow "Course 1"
    When I follow "Testing Demo"
    Then I should see "You are not enrolled in this course."

  Scenario: No permission
    Given I log in as "teacher"
    And I follow "Course 1"
    When I follow "Testing Demo"
    Then I should see "You do not have permission to choose your group."

  Scenario: No groups
    Given I log in as "student"
    And I follow "Course 1"
    When I follow "Testing Demo"
    Then I should see "There are no groups in this course"

  Scenario: Group selected
    Given the following "groups" exist:
    | name    | course | idnumber |
    | Group 1 | C1     | G1       |
    | Group 2 | C1     | G2       |
    Given the following "group members" exist:
    | user    | group |
    | student | G1    |
    And I log in as "student"
    And I follow "Course 1"
    When I follow "Testing Demo"
    Then I should see "Your group: Group 1"

  Scenario: Choose group
    Given the following "groups" exist:
    | name    | course | idnumber |
    | Group 1 | C1     | G1       |
    | Group 2 | C1     | G2       |
    And I log in as "student"
    And I follow "Course 1"
    When I follow "Testing Demo"
    And I set the field "group" to "Group 1"     
    And I press "Choose"
    Then I should see "Your group: Group 1"
