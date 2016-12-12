<?php

class Wristband extends GSObj
{
    private $_wristbandID = '';
    private $_hardwareID = '';
    private $_publicKey = '';
    private $_privateKey = '';
    private $_pinCode = '';
    private $_balance = '';
    
    public function __construct($attributes = array()){
        parent::__construct($attributes);
    }
    
    protected function wristbandID($value = null){
        if($value){
            $this->_wristbandID = $value;
        }
        else{
          return $this->_wristbandID;
        }
    }
    
    protected function hardwareID($value = null){
        if($value){
            $this->_hardwareID = $value;
        }
        else{
          return $this->_hardwareID;
        }
    }
    
    protected function publicKey($value = null){
        if($value){
            $this->_publicKey = $value;
        }
        else{
          return $this->_publicKey;
        }
    }
    
    protected function privateKey($value = null){
        if($value){
            $this->_privateKey = $value;
        }
        else{
          return $this->_privateKey;
        }
    }
    
     protected function pinCode($value = null){
        if($value){
            $this->_pinCode = $value;
        }
        else{
          return $this->_pinCode;
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
    
    protected function giftBalance($value = null){
        if($value){
            $this->_giftBalance = $value;
        }
        else{
          return $this->_giftBalance;
        }
    }
    
    function validateKey(){
       //Hacer la validacion con encriptado;
        return true;
    }
    
    function validatePayment($amount, $useGiftBalance, $orderDetail){
        $returnVal = 4;
        /*
         * 1 : Use only gift balance
         * 2 : Use gift + balance
         * 3 : Use balance
         * 4 : Not enough balance
         */
        
        $unknownProduct = false;
        foreach ($orderDetail as $value){
                foreach($value as $valc){
                                //0-ProductID
                                //1-Description
                                //2-price
                                //3-quantity
                                //4-ext price
                               
                                
            $productID =  $valc["ProductID"];
            if($productID != "1"){
                $unknownProduct = true;        
            }
                }
        }
        
        if($useGiftBalance && !$unknownProduct){
            if($this->_giftBalance>=$amount){ //We can complete purchase with gift money
                $returnVal= 1;
            }
            else { //Check if we can complete order with current balance
                if(($this->_giftBalance+$this->_balance)>=$amount){
                    $returnVal= 2;
                }
            }
        }
        else if($this->_balance>=$amount){
            $returnVal= 3;
        }
        
        return $returnVal;
    }
}