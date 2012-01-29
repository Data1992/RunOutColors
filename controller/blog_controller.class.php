<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'controller.class.php';
class BlogController extends Controller {

  protected $_models = array('blog_post');
  protected $_defaultAction = 'index';

  public function index() {
    $this->_tpl->assign('post', BlogPost::loadById(1));
  }

}