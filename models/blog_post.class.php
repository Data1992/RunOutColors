<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'model.class.php';
class BlogPost extends Model {

  private $_id;                     // post id for url
  private $_caption;                // headline
  private $_text;                   // text
  private $_comments = array();     // "BlogComment" array
  private $_images = array();       // "GalleryImage" array
  
  private $_created;                // timestamp of creation
  private $_edited;                 // timestamp of last edit
  
  public static function createNew() {
    $post = new self();
    $post->_created = $post->_edited = time();
    return $post;
  }
  
  public static function loadById($id) {
    // get db here somehow!
    $stmt = self::$_db->prepare('SELECT id, caption, text, created, edited FROM blog_post WHERE id = :id LIMIT 1');
    $stmt->execute(array(':id' => $id));
    
    $row = $stmt->fetch();
    if($row == false) {
      $error = $stmt->errorInfo();
      throw new ErrorException('BlogPost::loadById(): '.$error[2]);
    }
    return $row;
  }
  
}