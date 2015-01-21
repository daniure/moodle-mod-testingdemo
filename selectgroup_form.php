<?php

require_once($CFG->libdir . '/formslib.php');

class mod_testingdemo_selectgroup_form extends moodleform {

    function definition() {
        $mform = $this->_form;        
        $groups = $this->_customdata['groups'];

        $mform->addElement('header', 'groups', get_string('groups', 'testingdemo'));

        foreach ($groups as $group) {
            $mform->addElement('radio', 'group', '', $group->name, $group->id);
        }

        $this->add_action_buttons(false, get_string('choose', 'testingdemo'));
    }
}