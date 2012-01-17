<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require LIBRARY_DIR . DS . 'template.class.php';
require LIBRARY_DIR . DS . 'layout.class.php';
// TODO: If we got helpers insert them here ;)
 
abstract class Controller {

  private $_params = array();
  protected $_tpl;
  protected $_layoutTpl = null;
  
  public function setParameters(array $params) {
    $this->_params = $params;
  }
  
  public function getParameter($name, $default = null) {
    return array_key_exists($name, $this->_params) ? $this->_params[$name] : $default;
  }
  
  public function invokeAction($action) {
    if(method_exists($this, $action)) {
      $reflection = new ReflectionMethod($this, $action);
      if($reflection->isPublic()) {
        $this->_tpl = new Template(strtolower(str_replace('Controller', '', get_called_class())) . DS . $action . '.tpl.php');
        // TODO: Calling appropriate action method and use result for template!
        $this->$action();
      } else throw new ErrorException('<i>'.get_called_class().'::'.$action.'()</i> could not be called (has to be public!).');
    } else throw new ErrorException('<i>'.get_called_class().'::'.$action.'()</i> does not exist.');
  }
  
  public function setLayout($layoutTpl) {
    $this->_layoutTpl = new Layout($layoutTpl);
  }
  
  public function parseOutput() {
    if($this->_layoutTpl != null) {
      $this->_layoutTpl->assign('content', $this->_tpl->render());
      echo $this->_layoutTpl->render();
    } else echo $this->_tpl->render();
  }

}