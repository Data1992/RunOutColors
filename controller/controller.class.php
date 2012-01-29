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
  
  protected $_models = null;
  protected $_defaultAction = null;
  
  public function __construct() {
    if(is_array($this->_models)) {
      foreach($this->_models as $model) {
        $modelFile = MODEL_DIR . DS . $model . '.class.php';
        if(file_exists($modelFile) && is_readable($modelFile))
          require_once $modelFile;
        else throw new ErrorException('Model <i>'.$model.'</i> does not exist.');
      }
    }
  }
  
  public function setParameters(array $params) {
    $this->_params = $params;
  }
  
  public function setLayout($layoutTpl) {
    $this->_layoutTpl = new Layout($layoutTpl);
  }
  
  public function setDbConnection($db) {
    $this->_db = $db;
    foreach($this->_models as $model) {
      $modelClass = '';
      $parts = explode('_', $model);
      while(!empty($parts))
        $modelClass .= ucfirst(array_shift($parts));
      $modelClass::setDb($this->_db);
    }
  }
  
  public function assignToLayout($name, $value = null) {
    $this->_layoutTpl->assign($name, $value);
  }
  
  public function invokeAction($claimedAction) {
    $action = (!method_exists($this, $claimedAction) && $this->_defaultAction != null) ? $this->_defaultAction : $claimedAction;
    
    if(method_exists($this, $action)) {
      $reflection = new ReflectionMethod($this, $action);
      if($reflection->isPublic()) {
        $templateFile = strtolower(str_replace('Controller', '', get_called_class())) . DS . $action . '.tpl.php';
        $this->_tpl = new Template($templateFile);
        $this->$action();
      } else throw new ErrorException('<i>'.get_called_class().'::'.$action.'()</i> could not be called (has to be public!).');
    } else throw new ErrorException('Neither <i>'.get_called_class().'::'.$claimedAction.'()</i> nor <i>$_defaultAction</i> does exist.');
  }
  
  public function parseOutput() {
    if($this->_layoutTpl != null) {
      $this->_layoutTpl->assign('content', $this->_tpl->render());
      $this->_layoutTpl->assign('debug', (defined('DEBUG') && DEBUG === true) ? Debug::generateOutput() : '');
      echo $this->_layoutTpl->render();
    } else echo $this->_tpl->render();
  }

}