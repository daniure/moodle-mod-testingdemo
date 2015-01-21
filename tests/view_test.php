<?php

require_once(dirname(__FILE__) . '/base.php');

/**
 * @group mod_testingdemo
 */
class mod_testingdemo_view_testcase extends mod_testingdemo_testcase {

    private $course;
    private $instance;

    function setUp() {
        parent::setUp();
        $this->course = $this->generator->create_course();
        $this->instance = $this->modgenerator->create_instance(array('course' => $this->course->id));
    }

    function test() {
        $user = $this->generator->create_user();
        $this->generator->enrol_user($user->id, $this->course->id); // student
        $group1 = $this->generator->create_group(array('courseid' => $this->course->id));
        $group2 = $this->generator->create_group(array('courseid' => $this->course->id));
        $this->setUser($user);

        $output = testingdemo_view($this->instance->cmid);

        $this->expectOutputContains($group1->name);
        $this->expectOutputContains($group2->name);
    }

    function test_invalid_id() {
        $this->setExpectedException('moodle_exception', get_string('invalidcoursemodule', 'error'));

        testingdemo_view(0);
    }

    function test_not_logged_in() {
        $this->setExpectedException('require_login_exception');

        testingdemo_view($this->instance->cmid);
    }

    function test_not_enrolled() {
        $user = $this->getDataGenerator()->create_user();
        $roleid = $this->generator->create_role(array('archetype' => 'manager'));
        $context = context_course::instance($this->course->id);
        $this->generator->role_assign($roleid, $user->id, $context->id); // manager
        $this->setUser($user);
        
        $output = testingdemo_view($this->instance->cmid);

        $this->expectOutputContains(get_string('notenrolled', 'testingdemo'));
    }

    function test_no_permission() {
        $user = $this->generator->create_user();
        $roleid = $this->generator->create_role();
        $this->generator->enrol_user($user->id, $this->course->id, $roleid);
        $this->setUser($user);
        
        $output = testingdemo_view($this->instance->cmid);

        $this->expectOutputContains(get_string('nopermission', 'testingdemo'));
    }

    function test_no_groups() {
        $user = $this->generator->create_user();
        $this->generator->enrol_user($user->id, $this->course->id); // student
        $this->setUser($user);
        
        $output = testingdemo_view($this->instance->cmid);

        $this->expectOutputContains(get_string('nogroups', 'testingdemo'));
    }

    function test_group_selected() {
        $user = $this->generator->create_user();
        $this->generator->enrol_user($user->id, $this->course->id); // student
        $group1 = $this->generator->create_group(array('courseid' => $this->course->id));
        $group2 = $this->generator->create_group(array('courseid' => $this->course->id));
        $this->generator->create_group_member(array('groupid' => $group1->id, 'userid' => $user->id));
        $this->setUser($user);
        
        $output = testingdemo_view($this->instance->cmid);

        $this->expectOutputContains(get_string('yourgroup', 'testingdemo'));
        $this->expectOutputContains($group1->name);
    }
}
