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
define('TEMP_DIR',       ROOT_DIR . DS . 'temp');
define('GALLERY_PATH',   ROOT_DIR . DS . 'images' . DS . 'gallery');
define('DEBUG', false);

require LIBRARY_DIR . DS . 'functions.php';
require LIBRARY_DIR . DS . 'debug.class.php';
require LIBRARY_DIR . DS . 'configuration.class.php';
require LIBRARY_DIR . DS . 'database.class.php';
require LIBRARY_DIR . DS . 'request.class.php';
require LIBRARY_DIR . DS . 'routing.class.php';
require LIBRARY_DIR . DS . 'twitter.class.php';
require LIBRARY_DIR . DS . 'recaptchalib.php';

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

