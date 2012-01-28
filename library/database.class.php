<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
class Database extends PDO {

  public function __construct(array $options) {
    if(!array_key_exists('user', $options) ||
        !array_key_exists('password', $options) ||
        !array_key_exists('database', $options)) {
      throw new ErrorException("Not enough parameters (minimum is user, password and database).");
    }
    
    if(!array_key_exists('type', $options))
      $options['type'] = 'mysql';
    if(!array_key_exists('host', $options))
      $options['host'] = 'localhost';
    
    switch(strtolower($options['type'])) {
      case 'mysql':
      case 'pgsql':
        $dsn = $options['type'].':dbname='.$options['database'].';host='.$options['host'];
        parent::__construct($dsn, $options['user'], $options['password']);
        parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        break;
      default:
        throw new ErrorException("Database type <i>{$options['type']}</i> is currently not supported.");
    }
  }

}