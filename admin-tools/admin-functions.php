<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once '../_global.php';
 
function get_gallery_category_by_id($id) {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, name, description, directory, visible FROM gallery_category WHERE id = ? LIMIT 1');
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
    $stmt = $db->prepare('SELECT id, name, visible FROM gallery_category ORDER BY name ASC');
    $stmt->execute();
    return $stmt->fetchAll();
  } catch(Exception $e) {
    die($e->getMessage);
  }
}

function add_gallery_category($name, $description, $visible) {
  global $db;
  $directory = strtolower(preg_replace('#-+#', '-', preg_replace('#[^A-Za-z0-9]#', '-', $name)));
  try {
    $stmt = $db->prepare('INSERT INTO gallery_category(name, description, directory, visible) VALUES(?, ?, ?, ?) RETURNING id');
    $stmt->execute(array($name, $description, $directory, $visible));
    $result = $stmt->fetch();
    return $result['id'];
  } catch(Exception $e) {
    die($e->getMessage);
  }
}

function edit_gallery_category($id, $values) {
  global $db;
  $sql = 'UPDATE gallery_category SET ';
  $parameter = array();
  foreach($values as $key => $value) {
    $sql .= $key . ' = ?,';
    $parameter[] = $value;
  }
  $sql = substr($sql, 0, -1) . ' WHERE id = ?';
  $parameter[] = $id;
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute($parameter);
    return true;
  } catch(Exception $e) {
    return false;
  }
}

function delete_gallery_category($id) {
  global $db;
  try {
    $stmt = $db->prepare('DELETE FROM gallery_category WHERE id = ?');
    $stmt->execute(array($id));
    return true;
  } catch(Exception $e) {
    return false;
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
