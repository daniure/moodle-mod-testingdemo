<?php

require_once($CFG->dirroot . '/mod/testingdemo/selectgroup_form.php');
require_once($CFG->dirroot  . '/group/lib.php');

function testingdemo_user_groups() {
    global $COURSE, $DB, $USER;

    $sql = 'SELECT g.*
            FROM {groups} g
            JOIN {groups_members} gm ON gm.groupid = g.id
            WHERE g.courseid = :courseid AND gm.userid = :userid
            ORDER BY g.name';
    $params = array('courseid' => $COURSE->id, 'userid' => $USER->id);

    return $DB->get_records_sql($sql, $params);
}

function testingdemo_view($id) {
    global $COURSE, $PAGE, $USER;

    if (!$cm = get_coursemodule_from_id('testingdemo', $id)) {
        // 1. Invalid ID
        throw new moodle_exception('invalidcoursemodule');
    }

    // 2. Not logged in
    require_login($cm->course, false, $cm, true, CLI_SCRIPT);

    $urlparams = array('id' => $id);
    $url = new moodle_url('/mod/testingdemo/view.php', $urlparams);

    $PAGE->set_url($url);
    $PAGE->set_title($PAGE->activityrecord->name);
    $PAGE->set_heading($COURSE->fullname);

    $renderer = $PAGE->get_renderer('mod_testingdemo');

    if (!is_enrolled($PAGE->context)) {
        // 3. Not enrolled
        echo $renderer->not_enrolled_page();
        return;
    }

    if (!has_capability('mod/testingdemo:selectgroup', $PAGE->context)) {
        // 4. No permission
        echo $renderer->no_permission_page();
        return;
    }

    $groups = groups_get_all_groups($COURSE->id);

    if (!$groups) {
        // 5. No groups
        echo $renderer->no_groups_page();
        return;
    }

    if ($usergroups = testingdemo_user_groups()) {
        // 6. Group selected
        echo $renderer->group_selected_page($usergroups);
        return;
    }

    // 7. Form
    $customdata = array('groups' => $groups);
    $form = new mod_testingdemo_selectgroup_form($PAGE->url, $customdata);

    if ($data = $form->get_data()) {
        if (!empty($data->group)) {
            groups_add_member($data->group, $USER->id);
            redirect($PAGE->url);
        }
    }

    echo $renderer->select_group_page($form);
}
