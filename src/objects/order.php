<?php

class Order extends GSObj
{
    private $_orderID = '';
    private $_userID = '';
    private $_amount = '';
    private $_wristband = '';
    private $_date = '';
    private $_orderDetail = array();
    
    public function __construct($attributes = array()){
        parent::__construct($attributes);
    }
    
    protected function orderID($value = null){
        if($value){
            $this->_orderID = $value;
        }
        else{
          return $this->_orderID;
        }
    }
    
    protected function userID($value = null){
        if($value){
            $this->_userID = $value;
        }
        else{
          return $this->_userID;
        }
    }
    
    protected function amount($value = null){
        if($value){
            $this->_amount = $value;
        }
        else{
          return $this->_amount;
        }
    }
    
    protected function wristband($value = null){
        if($value){
            $this->_wristband = $value;
        }
        else{
          return $this->_wristband;
        }
    }
    
    protected function date($value = null){
        if($value){
            $this->_date = $value;
        }
        else{
          return $this->_date;
        }
    }
    
    protected function orderDetail($value = null){
        if($value){
            $this->_orderDetail = $value;
        }
        else{
          return $this->_orderDetail;
        }
    }
    
    public function addDetail($orderDetail){
        $_orderDetail[] = $orderDetail;
    }
}