<?php

class OrderDetail extends GSObj
{
    private $_orderDetailID = '';
    private $_productID = '';
    private $_quantity = '';
    private $_total = '';
    
    public function __construct($attributes = array()){
        parent::__construct($attributes);
    }
    
    protected function orderDetailID($value = null){
        if($value){
            $this->_orderDetailID = $value;
        }
        else{
          return $this->_orderDetailID;
        }
    }
    
    protected function productID($value = null){
        if($value){
            $this->_productID = $value;
        }
        else{
          return $this->_productID;
        }
    }
    
    protected function quantity($value = null){
        if($value){
            $this->_quantity = $value;
        }
        else{
          return $this->_quantity;
        }
    }
    
    protected function total($value = null){
        if($value){
            $this->_total = $value;
        }
        else{
          return $this->_total;
        }
    }
}