<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
class Routing {

  private $_command = null;
  private $_routes = array();

  public function __construct() {
    $this->_routes = Configuration::getInstance('routes.php');
    $this->_command = '/'.implode('/', array_values(array_diff(
      explode('/', $_SERVER['REQUEST_URI']),
      explode('/', $_SERVER['SCRIPT_NAME'])
    )));
    
    Debug::addMessage('[Routing]: Requesting '.$this->_command);
  }
  
  public function processRequest() {
    foreach($this->_routes as $name => $route) {
      $pattern = $route['route'];
      $placeholders = array();
      if(strpos($pattern, ':') !== false) {
        $count = preg_match_all("#:[A-Za-z][A-Za-z0-9]*#", $pattern, $placeholders);
        if($count == 0) 
          continue;
         
        $placeholders = $placeholders[0];
        if(count($placeholders) > 0) {
          for($i=0; $i<count($placeholders); $i++) {
            $var = $placeholders[$i] = substr($placeholders[$i], 1);
            if(!array_key_exists($var, $route['options']['parameters']))
              continue;
            
            $subst = $route['options']['parameters'][$var];
            switch($subst) {
              case 'numeric':
                $subst = '([1-9][0-9]*)';
                break;
              case 'email':
                $subst = '([A-Za-z][A-Za-z0-9-_.]*@[A-Za-z][A-Za-z0-9-_.]*[A-Za-z0-9]*.[a-z]{2,4})';
                break;
              default:
                break;
            }
            $pattern = str_replace(':'.$placeholders[$i], $subst, $pattern);
          }
          $parameters = array();
          if(preg_match_all("#{$pattern}#", $this->_command, $parameters, PREG_SET_ORDER)) {
            $parameters = $parameters[0];
            array_shift($parameters);
            
            $result = array(
              'name' => $name,
              'pattern' => $route['route'],
              'controller' => $route['options']['controller'],
              'action' => $route['options']['action'],
              'parameters' => array_combine($placeholders, $parameters),
            );
            return $result;
          }
        }
      } else if($pattern == $this->_command) {
        $result = array(
          'name' => $name,
          'pattern' => $route['route'],
          'controller' => $route['options']['controller'],
          'action' => $route['options']['action'],
        );
        return $result;
      }
    }
    
    throw new ErrorException("Could not find any matching route for request <code>{$this->_command}</code>.");
  }

}