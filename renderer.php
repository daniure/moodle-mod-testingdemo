<?php

class mod_testingdemo_renderer extends plugin_renderer_base {

    function group_selected_page(array $groups) {
        $groupnames = array();
        foreach ($groups as $group) {
            $groupnames[] = html_writer::tag('strong', $group->name);
        }
        $groupnames = implode(', ', $groupnames);
        $string = count($groups) == 1 ? 'yourgroup' : 'yourgroups';
        $content = html_writer::div(get_string($string, 'testingdemo') . ': ' . $groupnames);
        return $this->view_page($content);
    }

    function no_groups_page() {
        $msg = html_writer::div(get_string('nogroups', 'testingdemo'), 'warning');
        return $this->view_page($msg);
    }

    function not_enrolled_page() {
        $msg = html_writer::div(get_string('notenrolled', 'testingdemo'), 'warning');
        return $this->view_page($msg);
    }

    function no_permission_page() {
        $msg = html_writer::div(get_string('nopermission', 'testingdemo'), 'warning');
        return $this->view_page($msg);
    }

    function select_group_page(moodleform $form) {
        return $this->view_page($form->render());
    }

    private function view_page($content) {
        global $PAGE;

        $output = $this->header();
        if ($PAGE->activityrecord->intro) {
            $intro = file_rewrite_pluginfile_urls($PAGE->activityrecord->intro, 'pluginfile.php',
                                                  $PAGE->context->id, 'mod_testingdemo', 'intro', null);
            $intro = format_text($intro, $PAGE->activityrecord->introformat);
            $output .= html_writer::div($intro, 'generalbox');
        }
        $output .= $content;
        $output .= $this->footer();

        return $output;
    }
}
