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
$page = (isset($_GET['page']) && in_array($_GET['page'], array('home', 'artist', 'imprint')) ? $_GET['page'] : 'home');
if(isset($_POST['update-page'])) {
  $content = $_POST['page-content'];
  file_put_contents('../templates/static/'.$page.'.tpl.php', $content);
} else {
  $content = file_get_contents('../templates/static/'.$page.'.tpl.php');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>RunOutColors :: Static Pages Editor</title>
    <meta charset="utf-8" />
    <style type="text/css">
      * { margin: 0; padding: 0; }
      body { background: #111; color: #FFF; font-family: Arial, sans-serif; }
      h2 { color: #2B2; margin: 20px; }
      h3 { color: #2B2; margin-bottom: 5px; }
      div { margin-left: 50px; margin-bottom: 10px; padding: 10px; }
      a, a:link { color: #FFF; text-decoration: underline; }
      a:hover { color: #2B2; }
      
      #links { position: fixed; top: 0; right: -3px; background: #FFF; border: 3px solid #2B2; }
      #links a { color: black; }
      
      #page-form { position: relative; left: 50px; width: 60%; display: block; }
      #page-form input[type=submit] { width: 100px; padding: 2px; }
      ul { list-style: none; }
      ul li { float: left; width: 100px; }

    </style>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="../js/ckeditor/adapters/jquery.js"></script>
  </head>
  <body>
    <h2>RunOutColors :: Static Pages Editor</h2>
    <div id="links">
      <a href="index.php">Index</a> 
    </div>
    <div id="pages">
      <ul>
        <li><a href="static-editor.php?page=home">Home</a></li>
        <li><a href="static-editor.php?page=artist">Artist</a></li>
        <li><a href="static-editor.php?page=imprint">Imprint</a></li>
      </ul>
    </div>
    <form method="post" id="page-form">
      <input type="hidden" name="page" value="<?php echo $page; ?>" />
      <textarea id="editor" name="page-content">
<?php echo $content; ?>
      </textarea><br />
      <input type="submit" name="update-page" value="Absenden" />
    </form>
    <script type="text/javascript">
      //<![CDATA[
      $(function() {
        var config = { 
          toolbar: [ 
            ['Bold', 'Italic','Underline','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList', 'BulletedList', '-', 'Link', 'Unlink'], 
            ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak']         
          ],
          height: 500,
          width: 800,
        };
        $('#editor').ckeditor(config);
      });
      //]]>
    </script>
  </body>
</html>