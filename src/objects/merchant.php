<?php

class Merchant extends GSObj
{
    private $_merchantID = '';
    private $_contactName = '';
    private $_companyName = '';
    private $_email = '';
    private $_phone = '';
    private $_bankAccount = '';
    private $_bankName = '';
    private $_rfc = '';
    private $_branchList = null;
    
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
    
    protected function contactName($value = null){
        if($value){
            $this->_contactName = $value;
        }
        else{
          return $this->_contactName;
        }
    }
    
    protected function companyName($value = null){
        if($value){
            $this->_companyName = $value;
        }
        else{
          return $this->_companyName;
        }
    }
    
    protected function email($value = null){
        if($value){
            $this->_email = $value;
        }
        else{
          return $this->_email;
        }
    }
    
    protected function phone($value = null){
        if($value){
            $this->_phone = $value;
        }
        else{
          return $this->_phone;
        }
    }
    
    protected function bankAccount($value = null){
        if($value){
            $this->_bankAccount = $value;
        }
        else{
          return $this->_bankAccount;
        }
    }
    
    protected function bankName($value = null){
        if($value){
            $this->_bankName = $value;
        }
        else{
          return $this->_bankName;
        }
    }
    
    protected function rfc($value = null){
        if($value){
            $this->_rfc = $value;
        }
        else{
          return $this->_rfc;
        }
    }
    
    protected function branchList($value = null){
        if($value){
            $this->_branchList = $value;
        }
        else{
          return $this->_branchList;
        }
    }
    
}