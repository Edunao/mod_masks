<?php

namespace mod_masks;

require_once(dirname(__FILE__).'/mask_family_note.class.php');
require_once(dirname(__FILE__).'/mask_family_question.class.php');

class mask_families_manager{
    private static $families   = null;
    private static $typefamily = null;

    //-------------------------------------------------------------------------
    // Private utility methods

    private static function populateFamilyList(){
        if ( self::$families == null ){
            self::$families = array(
                'note'       => new mask_family_note,
                'question'   => new mask_family_question,
            );
        }
    }

    //-------------------------------------------------------------------------
    // Public API

    /**
     * Get an array of integer values representing style ids to preesent in the style selector menu
     * @return array of integer styles with '-1' values representing group separators (for use in menu construction)
     */
    public static function getFamilies(){
        self::populateFamilyList();
        return self::$families;
    }
}

