@mod @mod_testingdemo @javascript
Feature: Create Testing Demo activities

  Scenario: Create an activity    
    Given the following "users" exist:
    | username | firstname | lastname | email               |
    | teacher  | Teacher   | User     | teacher@example.com |
    And the following "courses" exist:
    | fullname | shortname | category |
    | Course 1 | C1        |        0 |
    And the following "course enrolments" exist:
    | user    | course | role           |
    | teacher | C1     | editingteacher |
    And I log in as "teacher"
    And I follow "Course 1"
    And I turn editing mode on
    When I add a "Testing demo" to section "1" and I fill the form with:
    | Name        | Testing activity name        |
    | Description | Testing activity description |
    Then I should see "Testing activity name"
