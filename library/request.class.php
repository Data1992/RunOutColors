<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
class Request {

  public static function getValue($name, $default = null) {
    if(array_key_exists($name, $_REQUEST))
      return $_REQUEST[$name];
    else return $default;
  }
  
  public static function getValues(array $nameDefaultArray) {
    $values = array();
    foreach($nameDefaultArray as $name => $default) {
      $values[$name] = self::getValue($name, $default);
    }
    
    return $values;
  }
  
  public static function getAllValues(array $excludes = null) {
    $values = array();
    foreach($_REQUEST as $name => $value) {
      if($excludes == null || !in_array($name, $excludes))
        $values[$name] = $_REQUEST[$name];
    }
    
    return $values;
  }

}