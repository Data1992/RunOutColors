<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  header('Location: index.php');
}
/************************************************************************/
$errorMessage = 'Fehler. Bitte versuche es erneut!';
$errorCode = 0;

if(isset($_POST['do-login'])) {
  if(!isset($_SESSION['csrf']) || !isset($_POST['csrf-token']) || $_SESSION['csrf'] !== $_POST['csrf-token']) {
    $errorCode = 1;
  } else {
    $user = require_once 'config.php';
    if(isset($_POST['username']) && isset($_POST['password'])) {
      if(array_key_exists(strtolower($_POST['username']), $user) 
          && $user[strtolower($_POST['username'])] === sha1($_POST['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $user[strtolower($_POST['username'])];
        header('Location: index.php');
      } else {
        $errorMessage = 'Benutzer und/oder Passwort ungültig. Bitte versuche es erneut!';
        $errorCode = 3;
      }
    } else {
      $errorMessage = 'Benutzer und/oder Passwort konnten nicht &uumlbertragen werden. Bitte versuche es erneut!';
      $errorCode = 2;
    }
  }
} elseif(isset($_POST['do-logout'])) {
  $_SESSION = array();
  session_destroy();
}

$key = substr(sha1(microtime()), 0, 20);
$_SESSION['csrf'] = $key;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>RunOutColors :: Login</title>
    <meta charset="utf-8" />
    <style type="text/css">
      * { margin: 0; padding: 0; }
      body { background: #111; color: #FFF; font-family: Arial, sans-serif; }
      h2 { color: #2B2; margin: 20px; }
      h3 { color: #2B2; margin-bottom: 5px; }
      
      #login-form { position: relative; left: 50px; width: 60%; }
      #login-form input { margin: 2px; }
      #login-form label { margin: 2px; width: 150px; display: inline-block; }
      #login-form input[type=submit] { width: 100px; padding: 2px; }
      
      #error-box { margin-top: 5px; border: 2px solid #F00; background: #F77; padding: 5px; }
    </style>
  </head>
  <body>
    <h2>RunOutColors :: Login</h2>
    <div id="login-form">
      <form method="post">
        <label for="username">Username:</label><input type="text" name="username" /><br />
        <label for="password">Passwort:</label><input type="password" name="password" /><br />
        <input type="hidden" name="csrf-token" value="<?php echo $key; ?>" />
        <input type="submit" name="do-login" value="Login" />
      </form>
<?php if($errorCode > 0): ?>
      <div id="error-box">
        <?php echo $errorMessage.' ('.str_pad($errorCode, 3, '0', STR_PAD_LEFT).')'; ?>
      </div>
<?php endif; ?>
    </div>
  </body>
</html>