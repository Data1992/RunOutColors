<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
class Debug {

  private static $_messages;
  private static $_n = 0;
  
  public static function addMessage($message, $prependStamp = true, $newLine = true) {
    self::$_messages[] = '#'.(self::$_n++).' '.$message.($newLine === true ? '<br />' : '')."\n";
  }
  
  public static function generateOutput() {
    $output  = '<div id="debug">'."\n";
    $output .= str_pad('== DEBUG: ', 140, '=', STR_PAD_RIGHT)."<br />\n";
    foreach(self::$_messages as $msg)
      $output .= $msg;
    $output .= str_repeat('=', 140)."<br />\n</div>\n";
    return $output;
  }

}