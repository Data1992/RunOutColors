<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'tmhTwitter/tmhOAuth.php';
require_once 'tmhTwitter/tmhUtilities.php';
class Twitter {

  private static $_authInstance = null;

  public static function authenticate(array $options) {
    self::$_authInstance = new tmhOAuth($options);
  }
  
  public static function getLatestTweets($num) {
    if(self::$_authInstance === null)
     throw new ErrorException('Twitter API requires authentication! Use <i>Twitter::authenticate()</i> first.');
    else
      $twitter = self::$_authInstance;
    
    try {
      $result = $twitter->request('GET', 'http://api.twitter.com/1/statuses/user_timeline.json', array(
        'include_entities' => '0',
        'screen_name' => 'Minifuzi',
        'count' => $num,
      ));
      if($result == 200) {
        $timeline = json_decode($twitter->response['response'], true);
        $tweets = array();
        foreach($timeline as $tweet) {
          $tweets[] = array(
            'id' => $tweet['id_str'],
            'user' => $tweet['user']['screen_name'],
            'image_url' => $tweet['user']['profile_image_url'],
            'text' => $tweet['text'],
          );
          
          file_put_contents(TEMP_DIR . DS . 'tweet_cache', serialize($tweets));
        }
        return $tweets;
      } else throw new ErrorException($twitter->response['error']);
    } catch(ErrorException $e) {
      if(file_exists(TEMP_DIR . DS . 'tweet_cache')) {
        $file = file(TEMP_DIR . DS . 'tweet_cache');
        $tweets = unserialize($file[0]);
        return $tweets;
      } else return 'twitter.com ist derzeit nicht erreichbar ('.$e->getMessage().').';
    }
  }

}