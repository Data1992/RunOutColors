<!DOCTYPE html>
<html>
<head>
  <title>RunOutColors</title>
  <meta charset="utf-8" />
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" type="text/css" href="/screen.css" type="screen" />
  <link rel="stylesheet" type="text/css" href="/jquery.lightbox.css" type="screen" />
  <script src="/js/jquery.min.js"></script>
  <script src="/js/jquery.lightbox.min.js"></script>
  <script src="/js/roc.js"></script>
</head>
<body>
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<?php $config = Configuration::getInstance('default.php'); ?>
  <meta property="fb:admins" content="<?php echo implode(',', $config['facebook']['admins']); ?>"/>
  <div id="root">
    <div id="banner"></div>
    <div id="menu">
      <ul>
        <li>
          <a href="/">
            <img src="/images/menu/1.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Home</span>
              <span class="descr">Neuigkeiten</span>
            </span>
          </a>
        </li>
        <li>
          <a href="#">
            <img src="/images/menu/2.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Artist</span>
              <span class="descr">Biographie</span>
            </span>
          </a>
        </li>
        <li>
          <a href="/blog">
            <img src="/images/menu/4.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Snaps</span>
              <span class="descr">Das Foto-Blog</span>
            </span>
          </a>
        </li>
        <li>
          <a href="/gallery">
            <img src="/images/menu/3.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">Gallery</span>
              <span class="descr">Impressionen</span>
            </span>
          </a>
        </li>
        <li>
          <a href="#">
            <img src="/images/menu/5.jpg" alt="" />
            <span class="active"></span>
            <span class="wrap">
              <span class="link">About</span>
              <span class="descr">Der letzte Rest</span>
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
