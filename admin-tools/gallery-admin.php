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
require 'admin-functions.php';

$selected_category = (isset($_POST['category']) ? intval($_POST['category']) : null);
if(isset($_POST['back'])) {
  $selected_category = null;
} else if(isset($_POST['create-category'])) {
  $new_category =  trim($_POST['create-category']);
  if($new_category != '')
    $selected_category = add_gallery_category($_POST['new-category'], $_POST['description'], 
      (isset($_POST['visible']) && ($_POST['visible'] == true) ? 'TRUE' : 'FALSE'));
} elseif(isset($_POST['edit-category'])) {
  edit_gallery_category(intval($selected_category), array(
    'name' => $_POST['category-name'],
    'visible' => (isset($_POST['category-visible']) && !empty($_POST['category-visible']) ? '1' : '0'),
    'description' => htmlentities($_POST['category-description']),
    'directory' => $_POST['category-directory'],
    'front_image' => $_POST['category-preview'],
  ));
} elseif(isset($_POST['delete-category'])) {
  delete_gallery_category($selected_category);
  $selected_category = null;
} elseif(isset($_POST['generate-thumbs'])) {
  $width = 0;
  $height = 0;
  $directory = '';
  switch($_POST['size']) {
    case 'smallest':
      $width = $height = 50;
      break;
    case 'small':
      $width = 200; $height = 150;
      break;
    default:
    case 'big':
      $width = 800; $height = 600;
      break;
  }
  create_gallery_thumbnails($selected_category, $width, $height, $_POST['size'], isset($_POST['force']));
} elseif(isset($_POST['confirm-manage-images'])) {
  if(isset($_POST['delete-image']))
    delete_gallery_images(array_values($_POST['delete-image']), $selected_category);
  if(isset($_POST['downloadable']))
    setdl_gallery_images(array_values($_POST['downloadable']), $selected_category);
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>RunOutColors :: Gallery Administration</title>
    <meta charset="utf-8" />
    <style type="text/css">
      * { margin: 0; padding: 0; }
      body { background: #111; color: #FFF; font-family: Arial, sans-serif; }
      h2 { color: #2B2; margin: 20px; }
      h3 { color: #2B2; margin-bottom: 5px; }
      a, a:link { color: #FFF; text-decoration: underline; }
      a:hover { color: #2B2; }
      div { margin-left: 50px; margin-bottom: 10px; padding: 10px; border: 1px solid #FFF; }
      
      form { margin-left: 15px; }
      form.inrow { display: inline; }
      select, input { margin: 2px; }
      select { width: 250px; border: 2px solid #FFF; background: #111; color: #FFF; padding: 2px; }
      input[type=submit] { border: 2px solid #FFF; background: #111; color: #FFF; padding: 3px 5px; width: 120px; }
      input[type=submit]:hover { background: #2B2; }
      input[type=text] { width: 242px; border: 2px solid #FFF; background: #111; color: #FFF; padding: 2px; }
      textarea { width: 353px; height: 70px; border: 2px solid #FFF; color: #FFF; background: #111; margin: 0px 0px 0px 2px; }
      
      input[name=delete-category] { background: #F00; font-weight: bold; }
      #upload-tool { width: 40%; }
      #choose-category-form, #edit-category-form { width: 40%; float: left; }
      #choose-category-form:after { clear: both; }
      #edit-category-form { position: relative; bottom: 52px; right: 40px; }
      #manage-images { width: 40%; float: left; margin-top: -185px; height: 400px; overflow: auto; }
      #manage-images td { padding: 5px; }
      #manage-images td img { border: 1px solid #FFF; }
      
      #links { position: fixed; top: 0; right: -3px; background: #FFF; border: 3px solid #2B2; }
      #links a { color: black; }
    </style>
    <script type="text/javascript">
      function confirmSubmit(message) {
        var agree = confirm(message);
        return agree == true;
      }
    </script>
  </head>
  <body>
    <h2>RunOutColors :: Gallery Administration</h2>
    <div id="links">
      <a href="index.php">Index</a> 
    </div>
    <div id="upload-tool">
      <script type="text/javascript">
        function popup(url, title) {
          var fenster = window.open(url, title, "width=1000, height=600, resizable=yes");
          fenster.focus();
          return false;
        }
      </script>
      <a href="gallery-upload.php?destroy=1" target="_blank" onClick="return popup(this.href, 'Upload Tool');">Upload Tool</a>
    </div>
    <div id="choose-category-form">
      <p><i>Kategorie ausw&auml;hlen:</i></p>
      <form method="post">
        <select name="category">
<?php $categories = get_gallery_categories(); ?>
<?php foreach($categories as $category): ?>
<?php $visible = (($category['visible']) == true) ? '' : ' (*)'; ?>
<?php $selected = (($selected_category == $category['id']) ? ' selected="selected"' : ''); ?>
          <option value="<?php echo $category['id']; ?>"<?php echo $selected; ?>><?php echo $category['name'].$visible; ?></option>
<?php endforeach; ?>
        </select>
        <input type="submit" name="choose-category" value="Ausw&auml;hlen" />
      </form><br />
      <p><i>Neue Kategorie erstellen:</i></p>
      <form method="post">
        <span>Name:</span><br />
        <input type="text" name="new-category" />
        <input type="checkbox" name="visible" checked="checked" /> Sichtbar?<br />
        <span>Beschreibung:</span><br />
        <textarea name="description"></textarea><br />
        <input type="submit" name="create-category" value="Erstellen" /><br />
      </form>
    </div>
<?php if($selected_category != null): ?>
<?php $category = get_gallery_category_by('id', $selected_category); ?>
    <div id="edit-category-form">
      <form method="post" class="inrow">
        <input type="hidden" name="category" value="<?php echo $category['id']; ?>" />
        <input type="submit" name="manage-images" value="Bilderverwaltung" /></form>
      <form method="post" class="inrow">
        <input type="hidden" name="category" value="<?php echo $category['id']; ?>" />
        <input type="submit" name="delete-category" value="L&Ouml;SCHEN" onclick="return confirmSubmit('Wirklich l&ouml;schen?');" />
      </form>
      <p style="margin-top: 5px;"><i>Kategorie bearbeiten:</i></p>
      <form method="post">
        <span>Name:</span><br />
        <input type="text" name="category-name" value="<?php echo $category['name']; ?>" />
        <input type="checkbox" name="category-visible"<?php echo ($category['visible'] == true) ? ' checked="checked"' : ''; ?> />
        <span>Sichtbar?</span><br />
        <span>Vorschaubild:</span><br />
        <select name="category-preview">
<?php $images = get_gallery_images($selected_category); ?>
<?php foreach($images as $image): ?>
          <option value="<?php echo $image['file']; ?>" <?php echo ($image['file'] == $category['front_image'] ? 'selected="selected"' : ''); ?>><?php echo $image['file']; ?></option>
<?php endforeach; ?>
        </select><br />
        <span>Verzeichnis:</span><br />
        <input type="text" name="category-directory" value="<?php echo $category['directory']; ?>" /><br />
        <span>Beschreibung:</span><br />
        <textarea name="category-description"><?php echo $category['description']; ?></textarea><br />
        <input type="hidden" name="category" value="<?php echo $category['id']; ?>" />
        <input type="submit" name="edit-category" value="Speichern" />
        <input type="submit" name="back" value="Zur&uuml;ck" /><br />
      </form>
      <p style="margin-top: 5px;"><i>Vorschaubilder erstellen:</i></p>
      <form method="post">
        <input type="hidden" name="category" value="<?php echo $category['id']; ?>" />
        <span>Gr&ouml;&szlig;e: </span><br />
        <select name="size">
          <option value="smallest">Sehr klein (50x50)</option>
          <option value="small">Klein (200x150)</option>
          <option value="big">Gro&szlig; (gleiches Verh&auml;ltnis)</option>
        </select><br />
        <input type="checkbox" name="force" /> Erzwingen<br />
        <input type="submit" name="generate-thumbs" value="Generieren" />
      </form>
    </div>
<?php if(isset($_POST['manage-images']) || isset($_POST['confirm-manage-images'])): ?>
<?php 
  $thumb_path = get_gallery_category_thumb_path($selected_category, 'smallest');
  $images = get_gallery_images($selected_category);
  $image_count = count($images);
?>
    <div id="manage-images">
      <p><i>Bilder:</i></p>
<?php if($image_count > 0): ?>
      <form method="post">
        <table>
<?php for($i=0; $i<$image_count; $i++): ?>
<?php if($i % 6 == 0): ?><tr><?php endif; ?>
          <td align="left">
            <center><img src="<?php echo $thumb_path . DS . $images[$i]['file']; ?>" /></center>
            <input type="checkbox" name="delete-image[<?php echo $i; ?>]" value="<?php echo $images[$i]['id']; ?>" /><small style="color: red">L&ouml;schen</small><br />
            <input type="checkbox" name="downloadable[<?php echo $i; ?>]" value="<?php echo $images[$i]['id']; ?>" <?php echo ($images[$i]['downloadable'] ? 'checked="checked"' : ''); ?> /><small>DL?</small>
          </td>
<?php if($i % 6 == 5): ?></tr><?php endif; ?>
<?php endfor; ?>
        </table>
        <input type="hidden" name="category" value="<?php echo $category['id']; ?>" />
        <input type="submit" name="confirm-manage-images" value="Best&auml;tigen" />
      </form>
<?php else: ?>
      <p>Keine Bilder in dieser Kategorie vorhanden.</p>
<?php endif; ?>
    </div>
<?php endif; ?>
<?php endif; ?>
  </body>
</html>

