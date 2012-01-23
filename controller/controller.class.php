<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require LIBRARY_DIR . DS . 'template.class.php';
require LIBRARY_DIR . DS . 'layout.class.php';
// TODO: If we got helpers insert them here ;)
 
abstract class Controller {

  protected $_params = array();
  protected $_db;
  protected $_tpl;
  protected $_layoutTpl = null;
  
  public function setParameters(array $params) {
    $this->_params = $params;
  }
  
  public function setLayout($layoutTpl) {
    $this->_layoutTpl = new Layout($layoutTpl);
  }
  
  public function setDbConnection($db) {
    $this->_db = $db;
  }
  
  public function assignToLayout($name, $value = null) {
    $this->_layoutTpl->assign($name, $value);
  }
  
  public function invokeAction($action) {
    if(method_exists($this, $action)) {
      $reflection = new ReflectionMethod($this, $action);
      if($reflection->isPublic()) {
        $this->_tpl = new Template(strtolower(str_replace('Controller', '', get_called_class())) . DS . $action . '.tpl.php');
        $this->$action();
      } else throw new ErrorException('<i>'.get_called_class().'::'.$action.'()</i> could not be called (has to be public!).');
    } else throw new ErrorException('<i>'.get_called_class().'::'.$action.'()</i> does not exist.');
  }
  
  public function parseOutput() {
    if($this->_layoutTpl != null) {
      $this->_layoutTpl->assign('content', $this->_tpl->render());
      $this->_layoutTpl->assign('debug', (defined('DEBUG') && DEBUG === true) ? Debug::generateOutput() : '');
      echo $this->_layoutTpl->render();
    } else echo $this->_tpl->render();
  }

}