<?php

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/testingdemo/locallib.php');

$id = required_param('id', PARAM_INT);

testingdemo_view($id);

