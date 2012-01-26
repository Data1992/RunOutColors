<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'controller.class.php';
class StaticController extends Controller {

  public function home() {
    $db = $this->_db;
    $stmt = $db->prepare('SELECT s.id, sv.id, sv.content, sv.stamp FROM static s, static_version sv
      WHERE s.version = sv.id AND s.id = "home" LIMIT 1');
    $stmt->execute();
    if($stmt->rowCount() > 0) {
      $row = $stmt->fetch();
      $this->_tpl->assign('message', $row['content']);
      $this->_tpl->assign('timestamp', $row['stamp']);
    } else throw new ErrorException('Static page <i>home</i> does not exist.');
  }
  
  public function imprint() {
  
  }

}