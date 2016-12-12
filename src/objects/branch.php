<?php

class Branch extends GSObj
{
    private $_branchID = '';
    private $_merchantID = '';
    private $_name = '';
    
    public function __construct($attributes = array()){
        parent::__construct($attributes);
    }
    
    protected function merchantID($value = null){
        if($value){
            $this->_merchantID = $value;
        }
        else{
          return $this->_merchantID;
        }
    }
    
    protected function branchID($value = null){
        if($value){
            $this->_branchID = $value;
        }
        else{
          return $this->_branchID;
        }
    }
    
    protected function name($value = null){
        if($value){
            $this->_name = $value;
        }
        else{
          return $this->_name;
        }
    }
    
}