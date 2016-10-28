<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/masks/backup/moodle2/restore_masks_stepslib.php');

/**
 * Provides all the settings and steps to perform one complete restore of the masks activity
 */
class restore_masks_activity_task extends restore_activity_task {

    /**
     * No masks settings
     */
    protected function define_my_settings() {
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        $this->add_step(new restore_masks_activity_structure_step('masks_structure', 'masks.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();
        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules[] = new restore_decode_rule('MASKSVIEWBYID', '/mod/masks/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('MASKSSETINDEX', '/mod/masks/index.php?id=$1', 'course');
        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * label logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('masks', 'add', 'view.php?id={course_module}', '{masks}');
        $rules[] = new restore_log_rule('masks', 'update', 'view.php?id={course_module}', '{masks}');
        $rules[] = new restore_log_rule('masks', 'view', 'view.php?id={course_module}', '{masks}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('masks', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}

