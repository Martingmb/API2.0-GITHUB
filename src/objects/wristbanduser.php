<?php

class WristbandUser extends GSObj
{
    private $_userID = '';
    private $_password = '';
    private $_userName = '';
    private $_name = '';
    private $_lastName = '';
    private $_balance = '';
    private $_gift = '';
   
    
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
    
    protected function name($value = null){
        if($value){
            $this->_name = $value;
        }
        else{
          return $this->_name;
        }
    }
    
    protected function lastName($value = null){
        if($value){
            $this->_lastName = $value;
        }
        else{
          return $this->_lastName;
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
    
    protected function balance($value = null){
        if($value){
            $this->_balance = $value;
        }
        else{
          return $this->_balance;
        }
    }
    
    protected function gift($value = null){
        if($value){
            $this->_gift = $value;
        }
        else{
          return $this->_gift;
        }
    }
    
    protected function wristbandID($value = null){
        if($value){
            $this->_wristbandID = $value;
        }
        else{
          return $this->_wristbandID;
        }
    }
}