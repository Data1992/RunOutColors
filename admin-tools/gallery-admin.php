<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
session_start();
require 'admin-functions.php';
define('GALLERY_PATH', '../images/gallery');

$selected_category = null;
if(isset($_POST['create-category'])) {
  $new_category =  trim($_POST['create-category']);
  if($new_category != '')
    $selected_category = add_gallery_category($_POST['new-category'], $_POST['description'], isset($_POST['visible']) && ($_POST['visible'] == true));
} elseif(isset($_POST['choose-category'])) {
  $selected_category = intval($_POST['category']);
} elseif(isset($_POST['edit-category'])) {
  edit_gallery_category(intval($_POST['category']), array(
    'name' => $_POST['category-name'],
    'visible' => (isset($_POST['category-visible']) && !empty($_POST['category-visible']) ? '1' : '0'),
    'description' => htmlentities($_POST['category-description']),
    'directory' => $_POST['category-directory'],
  ));
} elseif(isset($_POST['delete-category'])) {
  delete_gallery_category(intval($_POST['category']));
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
      div { margin-left: 50px; margin-bottom: 10px; padding: 10px; border: 1px solid #FFF; width: 70%; }
      
      form { margin-left: 15px; }
      form.inrow { display: inline; }
      select, input { margin: 2px; }
      select { width: 250px; border: 2px solid #FFF; background: #111; color: #FFF; padding: 2px; }
      input[type=submit] { border: 2px solid #FFF; background: #111; color: #FFF; padding: 3px 5px; width: 120px; }
      input[type=submit]:hover { background: #2B2; }
      input[type=text] { width: 242px; border: 2px solid #FFF; background: #111; color: #FFF; padding: 2px; }
      textarea { width: 353px; height: 70px; border: 2px solid #FFF; color: #FFF; background: #111; margin: 0px 0px 0px 2px; }
      
<?php if($selected_category != null): ?>
      #choose-category-form { opacity: 0.4; }
      #choose-category-form:hover { opacity: 1 }
      input[name=delete-category] { background: #F00; font-weight: bold; }
<?php endif; ?>
      #choose-category-form, #edit-category-form { width: 40%; float: left; }
      #choose-category-form:after { clear: both; }
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
<?php $category = get_gallery_category_by_id($selected_category); ?>
    <div id="edit-category-form">
      <form method="post" class="inrow"><input type="submit" name="generate-thumbs" value="Thumbnails" /></form>
      <form method="post" class="inrow"><input type="submit" name="manage-images" value="Bilderverwaltung" /></form>
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
        <span>Verzeichnis:</span><br />
        <input type="text" name="category-directory" value="<?php echo $category['directory']; ?>" /><br />
        <span>Beschreibung:</span><br />
        <textarea name="category-description"><?php echo $category['description']; ?></textarea><br />
        <input type="hidden" name="category" value="<?php echo $category['id']; ?>" />
        <input type="submit" name="edit-category" value="Speichern" />
        <input type="submit" name="back" value="Zur&uuml;ck" /><br />
      </form>
    </div>
<?php endif; ?>
  </body>
</html>

