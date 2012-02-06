<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once '../_global.php';
 
function get_gallery_category_by_id($id) {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, name, directory FROM gallery_category WHERE id = ? LIMIT 1');
    $stmt->execute(array($id));
    if($stmt->rowCount() > 0)
      return $stmt->fetch();
    else return false;
  } catch(Exception $e) {
    die($e->getMessage);
  }
}
 
function get_gallery_categories() {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, name FROM gallery_category');
    $stmt->execute();
    return $stmt->fetchAll();
  } catch(Exception $e) {
    die($e->getMessage);
  }
}

function add_gallery_image($image, $category_id) {
  global $db;
  try {
    $stmt = $db->prepare('INSERT INTO gallery_image(file, category) VALUES(?, ?)');
    $stmt->execute(array($image, $category_id));
    return true;
  } catch(Exception $e) {
    return false;
  }
}
