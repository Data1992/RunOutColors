<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once '../_global.php';

function get_gallery_thumb_types() {
  return array('smallest', 'small', 'middle', 'big');
}

function get_gallery_category_thumb_path($category_id, $thumb_type) {
  $thumb_types = get_gallery_thumb_types();
  if(!in_array($thumb_type, $thumb_types))
    $thumb_type = 'middle';
  $category = get_gallery_category_by_id($category_id);
  return GALLERY_PATH . DS . $category['directory'] . DS . $thumb_type;
}

function create_gallery_thumbnails($category_id, $width, $height, $folder, $force = false) {  
  $category = get_gallery_category_by_id($category_id);
  if($category === false)
    return;
  $dir = GALLERY_PATH . DS . $category['directory'];
  $thumb_dir = $dir . DS . $folder;
  if(!is_dir($thumb_dir))
    mkdir($thumb_dir);
  
  $dh = opendir($dir);
  while(($file = readdir($dh)) !== false) {
    if(is_dir($dir . DS . $file))
      continue;
    
    $current_thumb = $thumb_dir . DS . basename($file);
    $create_thumbnail = false;
    if($force === true) $create_thumbnail = true;
    else if(file_exists($current_thumb)) {
      $info = getimagesize($current_thumb);
      if($info[0] != $width || $info[1] != $height)
        $create_thumbnail = true;
    } else $create_thumbnail = true;
    
    if($create_thumbnail) {
      $thumb_width = $width;
      $thumb_height = $height;
      $image = imagecreatefromjpeg($dir . DS . $file);
      $thumb = imagecreatetruecolor($width, $height);
      $image_w = imagesx($image);
      $image_h = imagesy($image);
      $image_ratio = round($image_w / $image_h, 2);
      $thumb_ratio = round($width / $height, 2);

      if($folder == 'big') {
        if($width > $image_w || $height > $image_h) {
          if($thumb_ratio > $image_ratio) {
            $thumb_width = $height * $image_ratio;
          } else {
            $thumb_height = $width / $image_ratio;
          }
        }
        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_w, $image_h);
      } elseif($image_ratio == $thumb_ratio) {
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $image_w, $image_h);
      } else {
        if($image_ratio < 1) {
          $part = floor($image_w / $thumb_ratio);
          $offset = floor(($image_h - $part) / 2);
          imagecopyresampled($thumb, $image, 0, 0, 0, $offset, $width, $height, $image_w, $part);
        } else {
          $part = floor($image_h * $thumb_ratio);
          $offset = floor(($image_w - $part) / 2); 
          imagecopyresampled($thumb, $image, 0, 0, $offset, 0, $width, $height, $part, $image_h);
        }
      }
      imagejpeg($thumb, $current_thumb, 100);
      imagedestroy($thumb);
      imagedestroy($image);
    }
  }
}
 
function get_gallery_category_by_id($id) {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, name, description, directory, visible FROM gallery_category WHERE id = ? LIMIT 1');
    $stmt->execute(array($id));
    if($stmt->rowCount() > 0)
      return $stmt->fetch();
    else return false;
  } catch(Exception $e) {
    die($e->getMessage());
  }
}
 
function get_gallery_categories() {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, name, visible FROM gallery_category ORDER BY name ASC');
    $stmt->execute();
    return $stmt->fetchAll();
  } catch(Exception $e) {
    die($e->getMessage());
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
    die($e->getMessage());
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

function get_gallery_images($category_id) {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, file FROM gallery_image WHERE category = ?');
    $stmt->execute(array($category_id));
    return $stmt->fetchAll();
  } catch(Exception $e) {
    die($e->getMessage());
  }
}

function delete_gallery_images($images, $category_id) {
  global $db;
  if(!is_array($images)) $images = (array)$images;
  // delete from file system
  $sql = 'SELECT i.file, c.directory FROM gallery_image i INNER JOIN gallery_category c
    ON i.category = c.id WHERE (';
  foreach($images as $image_id)
    $sql .= 'i.id = ? OR ';
  $sql = substr($sql, 0, -4) . ') AND i.category = ?';
  $parameters = $images;
  $parameters[] = $category_id;
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute($parameters);
    $image_info = $stmt->fetchAll();
    
    foreach($image_info as $info) {
      $base_path = GALLERY_PATH . DS . $info['directory'];
      $thumb_types = get_gallery_thumb_types();
      foreach($thumb_types as $thumb_type) {
        $thumbnail = $base_path . DS . $thumb_type . DS . $info['file'];
        if(file_exists($thumbnail)) {
          unlink($thumbnail);
        }
      }
      $original = $base_path . DS . $info['file'];
      if(file_exists($original)) {
        unlink($original);
      }
    }
    
    // delete from db
    $sql = 'DELETE FROM gallery_image WHERE ';
    foreach($images as $image_id) {
      $sql .= 'id = ? OR ';
    }
    $sql = substr($sql, 0, -4);
    $stmt = $db->prepare($sql);
    $stmt->execute($images);
    return true;
  } catch(Exception $e) {
    return false;
  }
  die;
}
