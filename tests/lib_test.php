<?php

require_once(dirname(__FILE__) . '/base.php');

/**
 * @group mod_testingdemo
 */
class mod_testingdemo_helloworld_testcase extends basic_testcase {

    function test_supports_mod_intro() {
        global $CFG;
        require_once("{$CFG->dirroot}/mod/testingdemo/lib.php");

        $result = testingdemo_supports(FEATURE_MOD_INTRO);

        $this->assertTrue($result);
    }
}

/**
 * @group mod_testingdemo
 */
class mod_testingdemo_lib_testcase extends mod_testingdemo_testcase {

    function test_add_instance() {
        global $DB;       

        $course = $this->generator->create_course();

        $data = new stdClass;
        $data->course = $course->id;
        $data->name = 'Activity name';
        $data->intro = 'Activity description';
        $data->introformat = FORMAT_HTML;

        $id = testingdemo_add_instance($data);

        $record = $DB->get_record('testingdemo', array('id' => $id));
        $this->assertInternalType('object', $record);
        $this->assertEquals($course->id, $record->course);
        $this->assertEquals('Activity name', $record->name);
        $this->assertEquals('Activity description', $record->intro);
        $this->assertEquals(FORMAT_HTML, $record->introformat);
        $this->assertTimeCurrent($record->timemodified);
    }

    function test_update_instance() {
        global $DB;       

        $generator = $this->getDataGenerator();
        $course = $this->generator->create_course();

        $data = new stdClass;
        $data->course = $course->id;
        $data->name = 'Old name';
        $data->intro = 'Old description';
        $data->timemodified = time() - 365 * 24 * 60 * 60;
        $instance = $this->modgenerator->create_instance($data);
        
        $data = new stdClass;
        $data->instance = $instance->id;
        $data->name = 'New name';
        $data->intro = 'New description';
        $result = testingdemo_update_instance($data);

        $this->assertTrue($result);
        $record = $DB->get_record('testingdemo', array('id' => $instance->id));
        $this->assertInternalType('object', $record);
        $this->assertEquals($instance->course, $record->course);
        $this->assertEquals('New name', $record->name);
        $this->assertEquals('New description', $record->intro);
        $this->assertEquals($instance->introformat, $record->introformat);
        $this->assertTimeCurrent($record->timemodified);
    }

    function test_delete_instance() {
        global $DB;       

        $generator = $this->getDataGenerator();
        $course = $this->generator->create_course();
        $instance = $this->modgenerator->create_instance(array('course' => $course->id));

        $result = testingdemo_delete_instance($instance->id);

        $this->assertTrue($result);
        $this->assertFalse($DB->record_exists('testingdemo', array('id' => $instance->id)));
    }
}
