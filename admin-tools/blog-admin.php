<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header('Location: login.php');
}
/************************************************************************/
require_once 'admin-functions.php';
$invalid = false;

if((isset($_GET['edit']) || isset($_GET['delete'])) && !isset($_GET['id'])) {
  $invalid = true;
} else {
  $action = (isset($_GET['edit'])) ? 'edit' : (isset($_GET['delete']) ? 'delete' : 'create');
  if($action == 'edit') {
    $id = intval($_GET['id']);
    if(false === ($post = get_blog_post_by_id($id)))
      $invalid = true;
  } else if($action == 'delete') {
    $id = intval($_GET['id']);
    delete_blog_post($id);
    $invalid = true;  // make invalid so refreshing is done
  }
}
  
if($invalid === true)
  header('Location: blog-admin.php');
else unset($invalid);

if(isset($_POST['send-post'])) {
  if($action == 'edit' && isset($_POST['id'])) {
    $post = update_blog_post($_POST['id'], array(
      'caption' => $_POST['caption'],
      'text' => $_POST['text'],
      'front_image' => (isset($_POST['front-image']) && intval($_POST['front-image']) > 0) ? intval($_POST['front-image']) : null,
    ));
    $id = $post['id'];
  } elseif($action == 'create') {
    $id = create_blog_post($_POST);
  }
  
  if(isset($_SESSION['attachments']) && count($_SESSION['attachments']) > 0) {
    add_blog_post_attachments($id, $_SESSION['attachments']);
    unset($_SESSION['attachments']);
  }

  header('Location: blog-admin.php?edit&id='.$id);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>RunOutColors :: Blog Administration</title>
    <meta charset="utf-8" />
    <style type="text/css">
      * { margin: 0; padding: 0; }
      body { background: #111; color: #FFF; font-family: Arial, sans-serif; }
      h2 { color: #2B2; margin: 20px; }
      h3 { color: #2B2; margin-bottom: 5px; }
      a, a:link { color: white; }
      a:hover { color: #2B2; }
      
      #links, #posts { margin-left: 50px; margin-bottom: 10px; padding: 10px; }
      #links { position: fixed; top: 0; right: -3px; background: #FFF; border: 3px solid #2B2; }
      #links a { color: black; }
      
      #posts { padding: 10px; border: 1px solid #FFF; width: 300px; font-size: 12px; float: left; min-height: 400px; }
      #posts ul { margin-top: 10px; list-style: square inside; }
      #posts ul a { text-decoration: none; }
      #posts ul a:hover { text-decoration: underline; }
      #edit-form { padding: 10px; border: 1px solid #FFF; margin-left: 25px; float: left; width: 700px; min-height: 400px; }
      
      select, input { margin: 2px; }
      select { width: 250px; border: 2px solid #FFF; background: #111; color: #FFF; padding: 2px; }
      input[type=submit] { border: 2px solid #FFF; background: #111; color: #FFF; padding: 3px 5px; width: 120px; }
      input[type=submit]:hover { background: #2B2; }
      input[type=text] { width: 90%; border: 2px solid #FFF; background: #111; color: #FFF; padding: 2px 0; }
      textarea { width: 90%; height: 200px; border: 2px solid #FFF; color: #FFF; background: #111; margin: 0px 0px 0px 2px; }
    </style>
    <link rel="stylesheet" type="text/css" href="fileupload/fileuploader.css" />
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="../js/ckeditor/adapters/jquery.js"></script>
    <script type="text/javascript" src="fileupload/fileuploader.js"></script>
    <script type="text/javascript">
      function confirmSubmit(message) {
        var agree = confirm(message);
        return agree == true;
      }
    </script>
  </head>
  <body>
    <h2>RunOutColors :: Blog Administration</h2>
    <div id="links">
      <a href="index.php">Index</a> 
    </div>
    <div id="posts">
      <a href="blog-admin.php?create"><b>Neuen Blog-Eintrag erstellen</b></a><br />
      <ul>
<?php $posts = get_blog_posts(array('id', 'caption', 'edited')); ?>
<?php foreach($posts as $post_info): ?>
        <li><a href="blog-admin.php?edit&id=<?php echo $post_info['id']; ?>">
          <?php echo $post_info['caption']; ?> (<?php echo date('d. M, H:i', strtotime($post_info['edited'])); ?>)
          <a href="blog-admin.php?delete&id=<?php echo $post_info['id']; ?>" title="L&ouml;schen" onClick="return confirmSubmit('Wirklich diesen Eintrag l&ouml;schen?');">
            <img src="page_white_delete.png" width="14" />
          </a>
        </a></li>
<?php endforeach; ?>
      </ul>
    </div>
    <div id="edit-form">
      <h3><?php echo ($action == 'create' ? 'Neuer Beitrag' : ($action == 'edit' ? 'Beitrag bearbeiten' : '')); ?></h3>
      <form method="post">
<?php if(isset($post)): ?>
        <span>erstellt: <i><?php echo date('d.m.Y H:i', strtotime($post['created'])); ?></i> |
        <span>zuletzt bearbeitet: <i><?php echo date('d.m.Y H:i', strtotime($post['edited'])); ?></i><br />
<?php endif; ?>
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>" />
        <i>Titel:</i><br /><input type="text" name="caption" <?php echo (isset($post) ? 'value="'.$post['caption'].'"' : ''); ?> /><br />
        <i>Inhalt:</i><br />
        <textarea name="text" id="editor"><?php echo (isset($post) ? $post['text'] : ''); ?></textarea>
<?php if(isset($post)): ?>
<?php $attachments = get_blog_post_attachments($post['id']); ?>
<?php if($attachments !== false && !empty($attachments)): ?>
        <i>Vorschaubild:</i><br />
        <select name="front-image">
          <option value="0">-- Kein Bild</option>
<?php foreach($attachments as $attachment): ?>
<?php $selected = ($attachment['id'] == $post['front_image']) ? ' selected="selected"' : ''; ?>
          <option value="<?php echo $attachment['id']; ?>"<?php echo $selected; ?>><?php echo $attachment['file']; ?></option>
<?php endforeach; ?>
        </select><br />
<?php endif; ?>
<?php endif; ?>
        <i>Anhänge:</i><br />
        <div id="file-upload">
          <noscript><p>Please enable Javascript to use file uploader.</p></noscript>
        </div>
        <input type="submit" name="send-post" value="Absenden" />
      </form>
      <script type="text/javascript">
      //<![CDATA[
      $(function() {
        var config = { 
          toolbar: [ 
            ['Bold', 'Italic','Underline','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList', 'BulletedList', '-', 'Link', 'Unlink'], 
            ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak']         
          ],
          height: 300,
          width: 650,
          uiColor: '#000',
        };
        $('#editor').ckeditor(config);
        
        var uploader = new qq.FileUploader({
          element: document.getElementById('file-upload'),
          action: 'upload.php',
          sizeLimit: 1024*1024*10
        });
      });
      //]]>
    </script>
    </div>
  </body>
</html>