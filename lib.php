<?php

function testingdemo_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO:
           return true;
        default:
            return null;
    }
}

function testingdemo_add_instance(stdClass $data, $mform=null) {
    global $DB;

    if (!isset($data->timemodified)) {
        $data->timemodified = time();
    }

    return $DB->insert_record('testingdemo', $data);
}

function testingdemo_update_instance(stdClass $data, $mform=null) {
    global $DB;

    $data->id = $data->instance;

    if (!isset($data->timemodified)) {
        $data->timemodified = time();
    }

    $DB->update_record('testingdemo', $data);

    return true;
}

function testingdemo_delete_instance($id) {
    global $DB;

    $cm = get_coursemodule_from_instance('testingdemo', $id);
    if (!$cm) {
        return false;
    }

    $DB->delete_records('testingdemo', array('id' => $id));

    return true;
}
