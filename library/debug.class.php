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
    $output  = '<div style="font-size: 11px; font-family: Courier, fixed; color: blue">'."\n";
    $output .= '===================================== DEBUG ====================================<br />'."\n";
    foreach(self::$_messages as $msg)
      $output .= $msg;
    $output .= '================================================================================<br />'."\n";
    $output .= '</div>'."\n";
    return $output;
  }

}