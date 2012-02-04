<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
session_start();
require 'admin-functions.php';
define('MAX_FILE_SIZE', 1024*1024);
define('GALLERY_PATH', '../images/gallery');

if(!isset($_SESSION['step']) || $_SESSION['step'] < 1 || $_SESSION['step'] > 2)
  $_SESSION['step'] = 1;
$errorOccured = false;
$errorMessage = "";

if($_SESSION['step'] == 1 && isset($_FILES['image'])) {
  $image = $_FILES['image'];
  switch($image['error']) {
    case UPLOAD_ERR_OK:
      break;
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
      $errorOccured = true;
      $errorMessage = "File is too large!";
      break;
    case UPLOAD_ERR_PARTIAL:
      $errorOccured = true;
      $errorMessage = "File could only be partially uploaded!";
      break;
    case UPLOAD_ERR_NO_FILE:
      $errorOccured = true;
      $errorMessage = "No file uploaded!";
  }
  
  if(!$errorOccured) {
    if($image['size'] > MAX_FILE_SIZE) {
      $errorOccured = true;
      $errorMessage = "File is too large!";
    } elseif(!is_uploaded_file($image['tmp_name'])) {
      $errorOccured = true;
      $errorMessage = "File does not exist!";
    } elseif(false === strpos($image['type'], 'image/')) {
      $errorOccured = true;
      $errorMessage = "File has not a valid format!";
    }
  }

  if(!$errorOccured) {
    $uploadFilePath = GALLERY_PATH . '/.upload/'.$image['name'];
    if(!move_uploaded_file($image['tmp_name'], $uploadFilePath)) {
      $errorOccured = true;
      $errorMessage = "Couldn't move uploaded file to gallery!";
    } else {
      $_SESSION['step'] = 2;
      $_SESSION['uploadedFile'] = $image['name'];
    }
  }
} elseif($_SESSION['step'] == 2 && isset($_POST['category'])) {
  $categoryFolder = GALLERY_PATH . '/' . $_POST['category'];
  if(!is_dir($categoryFolder) && !mkdir($categoryFolder)) {
    $errorOccured = true;
    $errorMessage = "Category folder does not exist and couldn't be created!";
  } else {
    $fullSourcePath = GALLERY_PATH . '/.upload/' . $_SESSION['uploadedFile'];
    $fullDestPath = GALLERY_PATH .'/' . $_POST['category'] . '/' . $_SESSION['uploadedFile'];
    if(!copy($fullSourcePath, $fullDestPath)) {
      $errorOccured = true;
      $errorMessage = "Impossible to copy file into category folder!";
    } else {
      if(!add_gallery_image($_SESSION['uploadedFile'], $_POST['category'])) {
        $errorOccured = true;
        $errorMessage = "Couldn't set category for image {$_SESSION['uploadedFile']}!";
      } else {
        $_SESSION['step'] = 1;
        unlink($fullSourcePath);
        unset($_SESSION['uploadedFile']);
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>RunOutColors :: Gallery Upload Tool</title>
    <meta charset="utf-8" />
    <style type="text/css">
      * { margin: 0; padding: 0; }
      body { background: #111; color: #FFF; font-family: Arial, sans-serif; }
      h2 { color: #0B75AF; margin: 20px; }
      h3 { color: #0B75AF; margin-bottom: 5px; }
      div { margin-left: 50px; margin-bottom: 10px; padding: 10px; }
      
      #upload-form, #category-form { border: 1px solid #FFF; width: 70%; }
      #error { border: 1px solid #F00; background: #F88; width: 70%; }
      input[type=file], input[type=submit]  { display: block; margin-top: 5px; }
      input[type=submit] {
        border: 1px solid #FFF; color: #FFF; background: #111; padding: 5px 10px; }
        
      #category-form, #category-form img { float: left; }
    </style>
  </head>
  <body>
    <h2>RunOutColors :: Gallery Upload Tool</h2>
<?php if($_SESSION['step'] == 1): ?>
    <div id="upload-form">
      <form enctype="multipart/form-data" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
        Bild: <input type="file" name="image" /><br />
        <input type="submit" value="Senden" />
      </form>
    </div>
<?php elseif($_SESSION['step'] == 2): ?>
    <div id="category-form">
      <img src="<?php echo $uploadFilePath; ?>" height="300" alt="<?php echo $image['name']; ?>" />
      <h3>Kategorie auswählen:</h3>
      <form method="post">
<?php $categories = get_gallery_categories(); ?>
<?php for($i=0; $i<count($categories);$i++): ?>
        <input type="radio"<?php echo $i==0 ? " checked" : ""; ?> name="category" value="<?php echo $categories[$i]['id']; ?>" />
        <?php echo $categories[$i]['name']; ?><br />
<?php endfor; ?>
        <div style="clear: both;"></div>
        <input type="submit" value="Weiter" />
      </form>
    </div>
<?php endif; ?>
<?php if($errorOccured === true): ?>
    <div id="error">
      <?php echo $errorMessage; ?>
    </div>
<?php endif; ?>
  </body>
</html>
