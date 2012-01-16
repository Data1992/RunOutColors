<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
class Configuration {

  private static $_instances = array();

  public static function getInstance($filename, $forceReload = false) {
    if(!array_key_exists($filename, self::$_instances) || $forceReload === true) {
      if(file_exists(CONFIG_DIR . DS . $filename) && is_readable(CONFIG_DIR . DS . $filename)) {
        $filecontent = file_get_contents(CONFIG_DIR . DS . $filename);
        $fileext = substr($filename, strrpos($filename, '.')+1);
        switch($fileext) {
          case 'php':
            self::$_instances[$filename] = include_once CONFIG_DIR . DS . $filename;
            break;
          default:
            throw new ErrorException("Extension <i>.$fileext</i> is currently not a valid config file format.");
        }
      } else throw new ErrorException("<i>".(DIRNAME . DS . $filename)."</i> does not exist in config dir.");
    }
    
    return (array_key_exists($filename, self::$_instances)) ? self::$_instances[$filename] : null;
  }

}