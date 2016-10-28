<?php

defined('MOODLE_INTERNAL') || die;

/**
 * Define all the backup steps that will be used by the backup_masks_activity_task
 */

/**
 * Define the complete masks structure for backup, with file and id annotations
 */
class backup_masks_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // The masks module stores user info.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $masks = new backup_nested_element('masks', array('id'), array('name'));
        
        //docs
        $docs = new backup_nested_element('docs');

        $doc = new backup_nested_element('doc', array('id'), array(
            'parentcm','created', 'filename', 'pages'
        ));

        $doc_pages = new backup_nested_element('doc_pages');
        
        $doc_page = new backup_nested_element('doc_page', array('id'), array(
            'pagenum', 'imagename', 'w', 'h'
        ));
        
        //pages
        $pages = new backup_nested_element('pages');
        $page = new backup_nested_element('page', array('id'), array(
            'orderkey', 'docpage', 'flags'
        ));
        $page_masks = new backup_nested_element('page_masks');
        $page_mask = new backup_nested_element('page_mask', array('id'), array(
            'x','y','w','h','style', 'question', 'flags'
        ));
        
        //question
        $questions = new backup_nested_element('questions');
        $question = new backup_nested_element('question', array('id'), array(
            'type','data'
        ));

        // Build the tree.
        $masks->add_child($questions);
        $questions->add_child($question);
        
        $masks->add_child($docs);
        $docs->add_child($doc);
        $doc->add_child($doc_pages);
        $doc_pages->add_child($doc_page);
        
        $masks->add_child($pages);
        $pages->add_child($page);
        $page->add_child($page_masks);
        $page_masks->add_child($page_mask);
        
        //user state
        $user_states = new backup_nested_element('user_states');
        $user_state = new backup_nested_element('user_state', array('id'), array(
            'user','failcount','state', 'firstview', 'lastupdate'
        ));
        $question->add_child($user_states);
        $user_states->add_child($user_state);
        
        // Define sources.
        $masks->set_source_table('masks', array('id' => backup::VAR_ACTIVITYID));
        $doc->set_source_table('masks_doc', array('parentcm' => backup::VAR_MODID));
        
        $doc_page->set_source_table('masks_doc_page', array('doc' => backup::VAR_PARENTID));
        $page->set_source_table('masks_page', array('parentcm' => backup::VAR_MODID));
        $page_mask->set_source_table('masks_mask', array('page' => backup::VAR_PARENTID));
        $question->set_source_table('masks_question', array('parentcm' => backup::VAR_MODID));
        
        if ($userinfo) {
            $user_state->set_source_table('masks_user_state', array('question' => backup::VAR_PARENTID));
        }
        
        // Define id annotations.
        $page_mask->annotate_ids('masks_question', 'question');
        $page->annotate_ids('masks_doc_page', 'docpage');
        
        //annotate files
        $doc_page->annotate_files('mod_masks', 'masks_doc_page', 'id');

        // Return the root element (masks), wrapped into standard activity structure.
        return $this->prepare_activity_structure($masks);
    }
}
