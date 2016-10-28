<?php


namespace mod_masks;

abstract class mask_family{
    
    //-------------------------------------------------------------------------
    // Protected Data

    protected $familyName   = null;
    protected $masksStyles  = null;
    protected $cssfile      = null;


    //-------------------------------------------------------------------------
    // public helper functions
    
    public function getStylesTag( ){
        return '<link rel="stylesheet" type="text/css" href="' . $this->cssfile . '">';
    }
    
    public function getCssFile(){
        return $this->cssfile; 
    }
    
    public function getMasksStyles(){
        return $this->masksStyles; 
    }
    
    public function getStyleClass(){
        return 'mask-family-' . $this->familyName ;
    }
    
    public function getFamilyName(){
        return $this->familyName; 
    }
}
