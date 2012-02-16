<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'controller.class.php';
class BlogController extends Controller {

  protected $_defaultAction = 'index';

  public function index() {
    $sql = 'SELECT id, caption, text, edited FROM blog_post ORDER BY edited DESC';
    if(!isset($this->_params['page']))
      $this->_params['page'] = 1;
    $posts_per_page = 5;
    
    $sql .= ' LIMIT 5 OFFSET '.((intval($this->_params['page']) - 1) * $posts_per_page);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();
    $posts = $stmt->fetchAll();
    
    $sql = 'SELECT COUNT(*) AS post_count FROM blog_post';
    $stmt = $this->_db->query($sql);
    $result = $stmt->fetch();
    $post_count = $result['post_count'];
    if(!empty($posts)) {
      $this->_tpl->assign('current_page', $this->_params['page']);
      $this->_tpl->assign('prev_page', ($this->_params['page'] > 1 ? ($this->_params['page'] - 1) : null));
      $this->_tpl->assign('next_page', ($this->_params['page'] * $posts_per_page < $post_count ? ($this->_params['page'] + 1) : null));
      $this->_tpl->assign('posts', $posts);
    } else throw new ErrorException('Page does not exist.');
  }

}