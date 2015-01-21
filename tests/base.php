<?php

abstract class mod_testingdemo_testcase extends advanced_testcase {

    protected $generator;
    protected $modgenerator;

    function setUp() {
        global $CFG;

        parent::setUp();

        require_once("{$CFG->dirroot}/mod/testingdemo/lib.php");
        require_once("{$CFG->dirroot}/mod/testingdemo/locallib.php");

        $this->resetAfterTest(true);
        $this->generator = $this->getDataGenerator();
        $this->modgenerator = $this->generator->get_plugin_generator('mod_testingdemo');
    }

    protected function expectOutputContains($text) {
        $this->expectOutputRegex('/' . preg_quote($text, '/') . '/');
    }
}
