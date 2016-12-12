<?php
//http://www.beaconfire-red.com/epic-stuff/better-getters-and-setters-php
class GSObj{
  public function __construct($attributes = Array()){
    // Apply provided attribute values
    foreach($attributes as $field=>$value){
      $this->$field = $value;
    }
  }

  function __set($name,$value){
    if(method_exists($this, $name)){
      $this->$name($value);
    }
    else{
      // Getter/Setter not defined so set as property of object
      $this->$name = $value;
    }
  }

  function __get($name){
    if(method_exists($this, $name)){
      return $this->$name();
    }
    elseif(property_exists($this,$name)){
      // Getter/Setter not defined so return property if it exists
      return $this->$name;
    }
    return null;
  }
}