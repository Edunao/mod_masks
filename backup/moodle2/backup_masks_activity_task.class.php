<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/masks/backup/moodle2/backup_masks_stepslib.php');

/**
 * Provides all the settings and steps to perform one complete backup of the masks activity
 */
class backup_masks_activity_task extends backup_activity_task {
    /**
     * No masks settings
     */
    protected function define_my_settings() {
    }

    /**
     * Defines activity specific steps for this task
     *
     * This method is called from {@link self::build()}. Activities are supposed
     * to call {self::add_step()} in it to include their specific steps in the
     * backup plan.
     */
    protected function define_my_steps() {
        $this->add_step(new backup_masks_activity_structure_step('masks_structure', 'masks.xml'));
    }

    /**
     * No content encoding needed for this activity
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the same content with no changes
     */
    static public function encode_content_links($content) {
        global $CFG;
        
        $base = preg_quote($CFG->wwwroot,"/");
        
         // Link to the list of masks
        $search="/(".$base."\/mod\/masks\/index.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@MASKSINDEX*$2@$', $content);

        // Link to masks view by moduleid
        $search="/(".$base."\/mod\/masks\/view.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@MASKSVIEWBYID*$2@$', $content);
        
        return $content;
    }
}
