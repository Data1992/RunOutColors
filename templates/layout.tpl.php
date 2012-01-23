<!DOCTYPE html>
<html>
<head>
  <title>RunOutColors</title>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="screen.css" type="screen" />
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/roc.js"></script>
</head>
<body>
  <div id="root">
    <div id="banner"></div>
    <div id="menu">
      <ul>
        <li>
          <a href="#">
            <img src="images/menu/1.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Startseite</span>
              <span class="descr">Neuigkeiten</span>
            </span>
          </a>
          <!--
          <div class="box">
            <a href="#">Website</a>
            <a href="#">Illustrations</a>
            <a href="#">Photography</a>
          </div>
          -->
        </li>
        <li>
          <a href="#">
            <img src="images/menu/2.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Link 2</span>
              <span class="descr">Subtitle 2</span>
            </span>
          </a>
        </li>
        <li>
          <a href="#">
            <img src="images/menu/3.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Link 3</span>
              <span class="descr">Subtitle 3</span>
            </span>
          </a>
        </li>
        <li>
          <a href="#">
            <img src="images/menu/4.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Link 4</span>
              <span class="descr">Subtitle 4</span>
            </span>
          </a>
        </li>
        <li>
          <a href="#">
            <img src="images/menu/5.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Link 5</span>
              <span class="descr">Subtitle 5</span>
            </span>
          </a>
        </li>
      </ul>
    </div>
    <div id="content">
      <div id="content-inner">
<?php echo $content; ?>
      </div>
    </div>
    <div id="footer">
      <div id="copyright"></div>
<?php echo $debug; ?>
    </div>
  </div>
</body>
</html>
