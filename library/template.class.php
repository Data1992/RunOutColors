<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
class Template {

  private $_values;
  private $_file;
  
  public function __construct($tplFile) {
    $this->_file = $tplFile;
  }
  
  public function assign($name, $value = null) {
    if(is_array($name)) {
      foreach($name as $varName => $varValue) {
        $this->_values[$varName] = $varValue;
      }
    } else $this->_values[$name] = $value;
  }
  
  public function render() {
    Debug::addMessage('['.get_called_class().']: rendering ' . TEMPLATE_DIR . DS . $this->_file);
    extract($this->_values);
    ob_start();
    require TEMPLATE_DIR . DS . $this->_file;
    return ob_get_clean()."\n";
  }

}