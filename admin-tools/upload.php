<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
session_start();
require_once 'fileupload/fileuploader.php';
require_once 'admin-functions.php';

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array("jpg", "jpeg", "gif", "png");
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$snapCat = get_gallery_category_by('directory', 'snapshots');
$imageName = $uploader->getFile()->getName();

$result = $uploader->handleUpload(GALLERY_PATH . '/snapshots/' . DS . $snapCat['download_directory'] . '/');
if(isset($result['error'])) {
  echo json_encode($result);
  return;
}

$imageId = add_gallery_image($imageName, $snapCat['id']);
$_SESSION['attachments'][] = $imageId;

// to pass data through iframe you will need to encode all html tags
echo json_encode(array('success' => 'success'));