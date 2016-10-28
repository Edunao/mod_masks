<?php

/**
 * Structure step to restore one masks activity
 */
class restore_masks_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {
        $userinfo = $this->get_setting_value('userinfo');

        $paths = array();
        $paths[] = new restore_path_element('masks', '/activity/masks');
        $paths[] = new restore_path_element('question', '/activity/masks/questions/question');
        $paths[] = new restore_path_element('doc', '/activity/masks/docs/doc');
        $paths[] = new restore_path_element('doc_page', '/activity/masks/docs/doc/doc_pages/doc_page');
        $paths[] = new restore_path_element('page', '/activity/masks/pages/page');
        $paths[] = new restore_path_element('page_mask', '/activity/masks/pages/page/page_masks/page_mask');
        
        if ($userinfo) {
            $paths[] = new restore_path_element('user_state', '/activity/masks/questions/question/user_states/user_state');
        }

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    protected function process_masks($data) {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $newitemid = $DB->insert_record('masks', $data);
        $this->apply_activity_instance($newitemid);
    }

    protected function process_doc($data) {
        global $DB;

        $data = (object) $data;
        $oldid = $data->id;
        $cm = get_coursemodule_from_instance('masks', $this->get_new_parentid('masks'));
        $data->parentcm = $cm->id;

        $data->created = $this->apply_date_offset($data->created);

        // insert the entry record
        $newitemid = $DB->insert_record('masks_doc', $data);
        $this->set_mapping('doc', $oldid, $newitemid, true); // childs and files by itemname
    }

    protected function process_doc_page($data) {
         global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $data->doc = $this->get_new_parentid('doc');
        
        // insert the entry record
        $newitemid = $DB->insert_record('masks_doc_page', $data);
        $this->set_mapping('doc_page', $oldid, $newitemid, true);
    }
    
    protected function process_page($data) {
         global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $cm = get_coursemodule_from_instance('masks', $this->get_new_parentid('masks'));
        $data->parentcm = $cm->id;
        
        $data->docpage = $this->get_mappingid('doc_page', $data->docpage);
        
        // insert the entry record
        $newitemid = $DB->insert_record('masks_page', $data);
        $this->set_mapping('page', $oldid, $newitemid, true); // childs and files by itemname
    }
    
    protected function process_page_mask($data) {
         global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $data->page = $this->get_new_parentid('page');
        $data->question = $this->get_mappingid('question', $data->question);
        
        // insert the entry record
        $DB->insert_record('masks_mask', $data);
    }
    
    protected function process_question($data) {
         global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $cm = get_coursemodule_from_instance('masks', $this->get_new_parentid('masks'));
        $data->parentcm = $cm->id;
        
        // insert the entry record
        $newitemid = $DB->insert_record('masks_question', $data);
        $this->set_mapping('question', $oldid, $newitemid, true); // childs and files by itemname
    }
    
    protected function process_user_state($data) {
         global $DB;

        $data = (object) $data;
        $oldid = $data->id;

        $data->question = $this->get_new_parentid('question');
        
        // insert the entry record
        $DB->insert_record('masks_user_state', $data);
    }    
    
    protected function after_execute() {
        $this->add_related_files('mod_masks', 'masks_doc_page', 'doc_page');
    }

}
