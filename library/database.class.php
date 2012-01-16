<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
class Database extends PDO {

  public function __construct(array $options) {
    if(!array_key_exists('user', $options) ||
        !array_key_exists('password', $options) ||
        !array_key_exists('schema', $options)) {
      throw new ErrorException("Not enough parameters (minimum is user, password and schema).");
    }
    
    if(!array_key_exists('type', $options))
      $options['type'] = 'mysql';
    if(!array_key_exists('host', $options))
      $options['host'] = 'localhost';
    
    switch(strtolower($options['type'])) {
      case 'mysql':
        $dsn = 'mysql:dbname='.$options['schema'].';host='.$options['host'];
        parent::__construct($dsn, $options['user'], $options['password']);
        break;
      default:
        throw new ErrorException("Database type <i>{$options['type']}</i> is currently not supported.");
    }
  }

}