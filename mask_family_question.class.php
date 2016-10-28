<?php

namespace mod_masks;

require_once(dirname(__FILE__).'/mask_family.class.php');

class mask_family_question extends mask_family{
    
    //-------------------------------------------------------------------------
    // basics
    
    public function __construct(){
        $this->familyName   = 'question';
        $this->cssfile      = '/mod/masks/mask_family_question.css';
        $this->masksStyles    = array( 0, 1, 2, 3, -1, 4, 5, 6, 7 ) ;
    }
    
}
