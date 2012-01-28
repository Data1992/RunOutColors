<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
class BlogPost {

  private $_id;                     // post id for url
  private $_caption;                // headline
  private $_text;                   // text
  private $_comments = array();     // "BlogComment" array
  private $_images = array();       // "GalleryImage" array
  
  private $_created;                // timestamp of creation
  private $_edited;                 // timestamp of last edit

  private function __construct() {}
  
  public static function createNew() {
    $post = new self();
    $post->_created = $post->_edited = time();
    return $post;
  }
  
  public static function loadById($id) {
    $stmt = $this->_db->prepare('SELECT id, caption, text, created, edited FROM blog_posts WHERE id = :id LIMIT 1');
    $stmt->execute(array(':id' => $id));
    
    $row = $stmt->fetch();
    return $row;
  }
  
}