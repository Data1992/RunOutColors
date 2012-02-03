<!DOCTYPE html>
<html>
<head>
  <title>RunOutColors</title>
  <meta charset="utf-8" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
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
          <a href="/">
            <img src="images/menu/1.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Startseite</span>
              <span class="descr">Neuigkeiten</span>
            </span>
          </a>
        </li>
        <li>
          <a href="/blog">
            <img src="images/menu/2.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Blog</span>
              <span class="descr">Tell The Story</span>
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
              <span class="link">About</span>
              <span class="descr"></span>
            </span>
          </a>
          <div class="box">
            <a href="#">Disclaimer</a>
            <a href="/imprint">Impressum</a>
          </div>
        </li>
      </ul>
    </div>
    <div id="content">
      <div id="content-inner">
<?php echo $content; ?>
      </div>
    </div>
    <div id="footer">
      <div id="copyright">
        &copy;<?php echo date('Y', time()); ?> RunOutColors
      </div>
<?php echo $debug; ?>
    </div>
  </div>
  <div id="advertising">
    <ul>
      <span>GoogleAds</span>
      <li>Werbung</li>
      <li>Werbung</li>
      <li>Werbung</li>
      <li>Werbung</li>
      <li>Werbung</li>
    </ul>
    <ul>
      <span>BackLinkSeller</span>
      <li>Werbung</li>
      <li>Werbung</li>
      <li>Werbung</li>
      <li>Werbung</li>
      <li>Werbung</li>
    </ul>
  </div>
  <div id="twitterfeed">
    <ul>
      <span>Latests Tweets</span>
<?php $tweets = Twitter::getLatestTweets(5); ?>
<?php if(is_array($tweets)): ?>
<?php   foreach($tweets as $tweet): ?>
      <li class="tweet">
        <img src="<?php echo $tweet['image_url']; ?>" />
        <a href="http://www.twitter.com/<?php echo $tweet['user']; ?>/status/<?php echo $tweet['id']; ?>" target="_blank">
          <?php echo $tweet['text']; ?>
        </a>
      </li>
<?php   endforeach; ?>
<?php else: ?>
      <li><?php echo $tweets; ?></li>
<?php endif; ?>
    </ul>
  </div>
</body>
</html>
