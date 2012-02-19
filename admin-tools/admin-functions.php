<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once '../_global.php';
define('GALLERY_WEB_PATH', '../images/gallery');

function rrmdir($dir) {
  if (is_dir($dir)) { 
    $objects = scandir($dir); 
    foreach ($objects as $object) { 
      if ($object != "." && $object != "..") { 
        if(filetype($dir."/".$object) == "dir")
          rrmdir($dir."/".$object);
        else unlink($dir."/".$object); 
      } 
    } 
    reset($objects); 
    rmdir($dir); 
  } 
}

/* GALLERY FUNCTIONS
 *************************************************
 * get_gallery_thumb_types
 * get_gallery_category_thumb_path
 * create_gallery_thumbnails 
 * get_gallery_category_by_id
 * get_gallery_category_by_folder
 * get_gallery_categories 
 * add_gallery_category 
 * edit_gallery_category
 * delete_gallery_category 
 * add_gallery_image 
 * setdl_gallery_images
 * get_gallery_images
 * delete_gallery_images
 */

function get_gallery_thumb_types() {
  return array('smallest', 'small', 'middle', 'big');
}

function get_gallery_category_thumb_path($category_id, $thumb_type) {
  $thumb_types = get_gallery_thumb_types();
  if(!in_array($thumb_type, $thumb_types))
    $thumb_type = 'middle';
  $category = get_gallery_category_by('id', $category_id);
  return GALLERY_WEB_PATH . DS . $category['directory'] . DS . $thumb_type;
}

function create_gallery_thumbnails($category_id, $width, $height, $folder, $force = false) {  
  $category = get_gallery_category_by('id', $category_id);
  if($category === false)
    return;
  $dir = GALLERY_PATH . DS . $category['directory'];
  $thumb_dir = $dir . DS . $folder;
  $orig_dir = $dir . DS . $category['download_directory'];
  if(!is_dir($thumb_dir))
    mkdir($thumb_dir);
  
  $dh = opendir($orig_dir);
  while(($file = readdir($dh)) !== false) {
    if(is_dir($orig_dir . DS . $file))
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
      $image = imagecreatefromjpeg($orig_dir . DS . $file);
      $thumb = imagecreatetruecolor($width, $height);
      $image_w = imagesx($image);
      $image_h = imagesy($image);
      $image_ratio = round($image_w / $image_h, 2);
      $thumb_ratio = round($width / $height, 2);

      if($folder == 'big') {
        if($thumb_ratio > $image_ratio) {
          $thumb_width = $height * $image_ratio;
        } else {
          $thumb_height = $width / $image_ratio;
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

function get_gallery_category_by($column, $value) {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, name, description, directory, download_directory, visible,
      front_image FROM gallery_category WHERE '.$column.' = ? LIMIT 1');
    $stmt->execute(array($value));
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
  $download_directory = substr(sha1(mt_rand().$name), 0, 25);
  try {
    mkdir(GALLERY_PATH . DS . $directory . DS . $download_directory, 0755, true);
    $stmt = $db->prepare('INSERT INTO gallery_category(name, description, directory, download_directory, visible) VALUES(?, ?, ?, ?, ?) RETURNING id');
    $stmt->execute(array($name, $description, $directory, $download_directory, $visible));
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
    $stmt = $db->prepare('SELECT id, directory FROM gallery_category WHERE id = ?');
    $stmt->execute(array($id));
    $category = $stmt->fetch();
    $categoryFolder = GALLERY_PATH . DS . $category['directory'];
    rrmdir($categoryFolder);
    $stmt = $db->prepare('DELETE FROM gallery_image WHERE category = ?');
    $stmt->execute(array($category['id']));
    $stmt = $db->prepare('DELETE FROM gallery_category WHERE id = ?');
    $stmt->execute(array($category['id']));
    return true;
  } catch(Exception $e) {
    return false;
  }
}

function add_gallery_image($image, $category_id) {
  global $db;
  try {
    $stmt = $db->prepare('INSERT INTO gallery_image(file, category) VALUES(?, ?) RETURNING id');
    $stmt->execute(array($image, $category_id));
    $result = $stmt->fetch();
    return $result['id'];
  } catch(Exception $e) {
    return false;
  }
}

function setdl_gallery_images($downloadables, $category_id) {
  global $db;
  $images = get_gallery_images($category_id);
  $image_count = count($images);
  try {
    $db->beginTransaction();
    for($i=0; $i<$image_count; $i++) {
      $images[$i]['downloadable'] = (in_array($images[$i]['id'], $downloadables)) ? 'TRUE' : 'FALSE';
      $stmt = $db->prepare('UPDATE gallery_image SET downloadable = ? WHERE id = ?');
      $stmt->execute(array($images[$i]['downloadable'], $images[$i]['id']));
    }
    $db->commit();
  } catch(Exception $e) {
    $db->rollBack();
  }
}

function get_gallery_images($category_id) {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, file, downloadable FROM gallery_image WHERE category = ?');
    $stmt->execute(array($category_id));
    return $stmt->fetchAll();
  } catch(Exception $e) {
    die($e->getMessage());
  }
}

function delete_gallery_images($images, $category_id) {
  global $db;
  if($images == null || (is_array($images) && empty($images)))
    return;
  
  if(!is_array($images)) $images = (array)$images;
  // delete from file system
  $sql = 'SELECT i.file, c.directory, c.download_directory FROM gallery_image i 
    INNER JOIN gallery_category c ON i.category = c.id WHERE (';
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
      $original = $base_path . DS . $info['download_directory'] . DS . $info['file'];
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


/* BLOG FUNCTIONS
 *************************************************
 * get_blog_posts
 * get_blog_post_by_id
 * update_blog_post
 * create_blog_post
 * add_blog_post_attachments
 */

function get_blog_posts($values) {
  global $db;
  $sql = 'SELECT ';
  foreach($values as $value) {
    $sql .= $value.', ';
  }
  $sql = substr($sql, 0, -2) . ' FROM blog_post ORDER BY edited DESC';
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  } catch(Exception $e) {
    return false;
  }
}

function get_blog_post_by_id($post_id) {
  global $db;
  try {
    $stmt = $db->prepare('SELECT id, caption, text, created, edited FROM blog_post WHERE id = ?');
    $stmt->execute(array($post_id));
    return $stmt->fetch();
  } catch(Exception $e) {
    return false;
  }
}

function update_blog_post($post_id, $data) {
  global $db;
  try {
    $stmt = $db->prepare('UPDATE blog_post SET caption = ?, text = ?, front_image = ?, edited = NOW() WHERE id = ?');
    $stmt->execute(array($data['caption'], $data['text'], $data['front_image'], $post_id));
    return get_blog_post_by_id($post_id);
  } catch(Exception $e) {
    return false;
  }
}

function create_blog_post($data) {
  global $db;
  try {
    $stmt = $db->prepare('INSERT INTO blog_post(caption, text, edited) VALUES(?, ?, NOW()) RETURNING id');
    $stmt->execute(array($data['caption'], $data['text']));
    $result = $stmt->fetch();
    return $result['id'];
  } catch(Exception $e) {
    return false;
  }
}

function delete_blog_post($post_id) {
  global $db;
  try {
    $images = array();
    $category = get_gallery_category_by('directory', 'snapshots');
    
    $stmt = $db->prepare('DELETE FROM blog_post_attachment WHERE post = ? RETURNING image');
    $stmt->execute(array($post_id));
    while(false !== ($row = $stmt->fetch()))
      $images[] = $row['image'];
    delete_gallery_images($images, $category['id']);
    $stmt = $db->prepare('DELETE FROM blog_post WHERE id = ?');
    $stmt->execute(array($post_id));
  } catch(Exception $e) {
    return false;
  }
}

function get_blog_post_attachments($post_id) {
  global $db;
  try {
    $stmt = $db->prepare('SELECT i.id, i.file, c.directory FROM blog_post_attachment a, gallery_image i,
      gallery_category c WHERE a.post = ? AND a.image = i.id AND c.id = i.category');
    $stmt->execute(array($post_id));
    return $stmt->fetchAll();
  } catch(Exception $e) {
    return false;
  }
}

function add_blog_post_attachments($post_id, $images) {
  global $db;
  foreach($images as $image) {
    try {
      $stmt = $db->prepare('INSERT INTO blog_post_attachment(post, image) VALUES(?, ?)');
      $stmt->execute(array($post_id, $image));
    } catch(Exception $e) {
      return false;
    }
  }
  
  return true;
}
