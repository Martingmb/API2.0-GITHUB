<?php

class Product extends GSObj
{
    private $_productID = '';
    private $_description = '';
    private $_unitPrice = '';
    private $_categoryID = '';
    private $_image = '';
    private $_available = '';
    
    public function __construct($attributes = array()){
        parent::__construct($attributes);
    }
    
    protected function productID($value = null){
        if($value){
            $this->_productID = $value;
        }
        else{
          return $this->_productID;
        }
    }
    
    protected function description($value = null){
        if($value){
            $this->_description = $value;
        }
        else{
          return $this->_description;
        }
    }
    
    protected function unitPrice($value = null){
        if($value){
            $this->_unitPrice = $value;
        }
        else{
          return $this->_unitPrice;
        }
    }
    
    protected function categoryID($value = null){
        if($value){
            $this->_categoryID = $value;
        }
        else{
          return $this->_categoryID;
        }
    }
    
     protected function image($value = null){
        if($value){
            $this->_image = $value;
        }
        else{
          return $this->_image;
        }
    }
    
    protected function available($value = null){
        if($value){
            $this->_available = $value;
        }
        else{
          return $this->_available;
        }
    }
}