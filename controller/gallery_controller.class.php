<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'controller.class.php';
class GalleryController extends Controller {

  public function index() {
    $stmt = $this->_db->prepare('SELECT id, name, description, front_image, directory FROM
      gallery_category ORDER BY name ASC');
    $stmt->execute();
    
    $this->_tpl->assign('galleryCount', $stmt->rowCount());
    $this->_tpl->assign('galleries', $stmt->fetchAll());
  }
  
  public function viewcategory() {
    $stmt = $this->_db->prepare('SELECT id, name, directory FROM gallery_category WHERE directory = ?');
    $stmt->execute(array($this->_params['category']));
    $category = $stmt->fetch();
    if($category === false)
      throw new ErrorException('Category <i>'.$this->_params['category'].'</i> was not found.');
    $this->_tpl->assign('category', $category);
    
    $stmt = $this->_db->prepare('SELECT id, file FROM gallery_image WHERE category = ?');
    $stmt->execute(array($category['id']));
    $images = $stmt->fetchAll();
    $this->_tpl->assign('directory', $this->_params['category']);
    $this->_tpl->assign('images', $images);
  }
  
  public function viewimage() {
    $stmt = $this->_db->prepare('SELECT i.id, i.file, c.directory, c.name FROM gallery_image i INNER JOIN gallery_category c
      ON i.category = c.id WHERE i.id = ? AND c.directory = ?');
    $stmt->execute(array($this->_params['id'], $this->_params['category']));
    $result = $stmt->fetch();
    if($result === false)
      throw new ErrorException('Image does not exist.');
    $this->_tpl->assign('result', $result);
  }

}
