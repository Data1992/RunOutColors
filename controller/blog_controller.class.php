<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'controller.class.php';
class BlogController extends Controller {

  protected $_defaultAction = 'index';

  public function index() {
    $stmt = $this->_db->prepare('SELECT caption, text, edited FROM blog_post WHERE id = 1');
    $stmt->execute();
    
    $row = $stmt->fetch();
    $this->_tpl->assign('post', $row);
  }

}