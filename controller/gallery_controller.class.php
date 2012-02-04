<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'controller.class.php';
class GalleryController extends Controller {

  public function index() {
    $stmt = $this->_db->prepare('SELECT id, name, description, front_image FROM
      gallery_category WHERE visible = TRUE');
    $stmt->execute();
    
    $this->_tpl->assign('galleryCount', $stmt->rowCount());
    $this->_tpl->assign('galleries', $stmt->fetchAll());
  }

}

