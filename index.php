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
define('MODEL_DIR',      ROOT_DIR . DS . 'models');
define('TEMP_DIR',       ROOT_DIR . DS . 'temp');
define('DEBUG', true);

require LIBRARY_DIR . DS . 'functions.php';
require LIBRARY_DIR . DS . 'debug.class.php';
require LIBRARY_DIR . DS . 'configuration.class.php';
require LIBRARY_DIR . DS . 'database.class.php';
require LIBRARY_DIR . DS . 'request.class.php';
require LIBRARY_DIR . DS . 'twitter.class.php';

// Localize datetime output
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
  $config['database']['database'].') - Using '.$db->getAttribute(PDO::ATTR_SERVER_VERSION));
// Parse request
$controller = Request::getValue('controller', 'static');
$action = Request::getValue('action', 'home');
$params = Request::getAllValues(array('controller', 'action'));
Debug::addMessage('[Request]: $controller='.$controller.', $action='.$action);
Debug::addMessage('[Request]: Other parameters: '.(count($params) > 0 ? print_r($params, true): '<i>none</i>'));
// Initialize twitter api
Twitter::authenticate($config['twitter']);

// Controller calling requirements:
//    - there has to be a correctly named controller file in the controller directory
//    - the controller file contains the controller class
//    - the action method exists in the controller object
//    - the action method is accessible in public context
$controllerFile = $controller . '_controller.class.php';
$controllerClass = ucfirst($controller).'Controller';
if(file_exists(CONTROLLER_DIR . DS . $controllerFile) && is_readable(CONTROLLER_DIR . DS . $controllerFile)) {
  require_once CONTROLLER_DIR . DS . $controllerFile;
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
  } else throw new ErrorException('Controller file does not contain class <i>'.$controllerClass.'</i>.', 0, E_USER_ERROR);
} else throw new ErrorException('<i>'.$controllerFile.'</i> does not exist.', 0, E_USER_ERROR);