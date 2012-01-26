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
    $fullTplPath = TEMPLATE_DIR . DS . $this->_file;
    if(file_exists($fullTplPath) && is_readable($fullTplPath)) {
      if(is_array($this->_values))
        extract($this->_values);
      ob_start();
      require TEMPLATE_DIR . DS . $this->_file;
      return ob_get_clean()."\n";
    } else throw new ErrorException('<i>'.$fullTplPath.'</i> does not exist in template directory.');
  }

}