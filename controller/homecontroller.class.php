<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'controller.class.php';
class HomeController extends Controller {

  public function index() {
    $this->_tpl->assign('message', '<h2>Hello World</h2>');
  }

}