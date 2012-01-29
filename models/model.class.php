<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
abstract class Model {

  protected static $_db;

  final private function __construct($db) {
    self::$_db = $db;
    $this->initialize();
  }
  
  protected function initialize() {}
  
  public static function setDb($db) {
    self::$_db = $db;
  }

}