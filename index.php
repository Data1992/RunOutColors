<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
try {
  require_once '_global.php';
  // Parse request
  /* old:
  $controller = Request::getValue('controller', 'static');
  $action = Request::getValue('action', 'home');
  $params = Request::getAllValues(array('controller', 'action'));
  */
  $router = new Routing();
  $request = $router->processRequest();
  $controller = $request['controller'];
  $action = $request['action'];
  $params = (isset($request['parameters']) ? $request['parameters'] : array());
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
} catch(Exception $e) {
  if(defined('DEBUG') && DEBUG === true) {
    handleException($e);
  } else {
    echo str_replace('##CONTENT##', $e->getMessage(), file_get_contents('templates/error.tpl.php'));
  }
}