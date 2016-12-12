<?php

class User extends GSObj
{
    private $_userID = '';
    private $_merchantID = '';
    private $_branchID = '';
    private $_userName = '';
    private $_password = '';
    private $_admin = '';
   
    
    public function __construct($attributes = array()){
        parent::__construct($attributes);
    }
    
    protected function userID($value = null){
        if($value){
            $this->_userID = $value;
        }
        else{
          return $this->_userID;
        }
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
    
    protected function userName($value = null){
        if($value){
            $this->_userName = $value;
        }
        else{
          return $this->_userName;
        }
    }
    
    protected function password($value = null){
        if($value){
            $this->_password = $value;
        }
        else{
          return $this->_password;
        }
    }
    
    protected function admin($value = null){
        if($value){
            $this->_admin = $value;
        }
        else{
          return $this->_admin;
        }
    }
}