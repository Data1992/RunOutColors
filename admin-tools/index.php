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
?>
<!DOCTYPE html>
<html>
  <head>
    <title>RunOutColors :: Administration</title>
    <meta charset="utf-8" />
    <style type="text/css">
      * { margin: 0; padding: 0; }
      body { background: #111; color: #FFF; font-family: Arial, sans-serif; margin: 20px; }
      h2 { color: #2B2; }
      h3 { color: #2B2; margin-bottom: 5px; }
      ul { list-style: none; }
      ul li { width: 250px; float: left; background: #FFF; padding: 5px; border: 3px solid #2B2; }
      ul li:not(:last-child) { border-right: none; }
      a, a:link { color: #000; text-decoration: underline; }
      a:hover { color: #2B2; }
      
      #logout-form { width: 60%; }
      #logout-form input { margin: 2px; }
      #logout-form input[type=submit] { width: 100px; padding: 2px; }
    </style>
  </head>
  <body>
    <h2>RunOutColors :: Administration</h2>
    <span>(eingeloggt als <?php echo $_SESSION['username']; ?>)</span><br />
    <div id="logout-form">
      <form method="post" action="login.php">
        <input type="submit" name="do-logout" value="Logout" />
      </form>
    </div>
    <div style="margin-left: 30px; margin-top: 10px;">
      <h3>Funktionen</h3>
      <ul>
        <li><a href="gallery-admin.php">Gallery Administration</a></li>
        <li><a href="gallery-upload.php">Gallery Upload Tool</a></li>
        <li><a href="static-editor.php">Static Pages Editor</a></li>
        <li><a href="blog-admin.php">Blog Administration</a></li>
      </ul>
    </div>
  </body>
</html>