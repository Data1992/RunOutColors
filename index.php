<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
define('DS', DIRECTORY_SEPARATOR); 
define('ROOT_DIR',       realpath(dirname(__FILE__)));
define('CONFIG_DIR',     ROOT_DIR . DS . 'config');
define('LIBRARY_DIR',    ROOT_DIR . DS . 'library');
define('CONTROLLER_DIR', ROOT_DIR . DS . 'controller');
define('TEMPLATE_DIR',   ROOT_DIR . DS . 'templates');
define('DEBUG', false);

require LIBRARY_DIR . DS . 'functions.php';
require LIBRARY_DIR . DS . 'debug.class.php';
require LIBRARY_DIR . DS . 'configuration.class.php';
require LIBRARY_DIR . DS . 'database.class.php';
require LIBRARY_DIR . DS . 'request.class.php';

setlocale(LC_ALL, (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'german' : 'de_DE.UTF-8');

// Exception handler is placed in functions.php!
set_exception_handler('handleException');
// Load main configuration
$config = Configuration::getInstance('default.php');
Debug::addMessage('[Configuration]: Loaded file '. CONFIG_DIR . DS . 'default.php');
// Connect to database
$db = new Database($config['database']);
Debug::addMessage('[Database]: Connection established ('.
  $config['database']['user'].'@'.$config['database']['host'].'/'.
  $config['database']['schema'].') - Using '.$db->getAttribute(PDO::ATTR_SERVER_VERSION));
// Parse request
$controller = Request::getValue('controller', 'home');
$action = Request::getValue('action', 'index');
$params = Request::getAllValues(array('controller', 'action'));
Debug::addMessage('[Request]: $controller='.$controller.', $action='.$action);
Debug::addMessage('[Request]: Other parameters: '.(count($params) > 0 ? print_r($params, true): '<i>none</i>'));

// Controller calling requirements:
//    - there has to be a correctly named controller file in the controller directory
//    - the controller file contains the controller class
//    - the action method exists in the controller object
//    - the action method is accessible in public context
$controllerFile = CONTROLLER_DIR . DS . $controller . 'controller.class.php';
$controllerClass = ucfirst($controller).'Controller';
if(file_exists($controllerFile) && is_readable($controllerFile)) {
  require_once $controllerFile;
  if(class_exists($controllerClass)) {
    // Create controller and invoke action, everything else is done there
    $controller = new $controllerClass();
    $controller->setParameters($params);
    $controller->setDbConnection($db);
    if(isset($config['template']['layout'])) {
      Debug::addMessage('[Layout]: Loaded layout '. TEMPLATE_DIR . DS . $config['template']['layout']);
      $controller->setLayout($config['template']['layout']);
    }
    Debug::addMessage('[Controller]: Invoking '.$controllerClass.'::'.$action.'()');
    $controller->invokeAction($action);
    $controller->parseOutput();
  } else throw new ErrorException('Controller file does not contain class <i>'.$controllerClass.'</i>.');
} else throw new ErrorException('<i>'.$controller.'.class.php</i> does not exist.');