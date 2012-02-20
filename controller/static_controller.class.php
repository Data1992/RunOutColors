<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require_once 'controller.class.php';
class StaticController extends Controller {

  public function home() {
    // static template only!
  }
  
  public function imprint() {
    // static template only!
  }
  
  public function artist() {
    // static template only!
  }
  
  public function contact() {
    $config = Configuration::getInstance('default.php');
    $this->_tpl->assign('recaptcha_key', $config['recaptcha']['public_key']);
    
    if(Request::getValue('send-form', false) === false) {
      $this->_tpl->assign('done', false);
    } else {
      $data = Request::getValues(array('from-name' => null, 'from-email' => null, 'message' => null));
      if(empty($data['from-name']) || empty($data['from-email']) || empty($data['message'])) {
        $this->_tpl->assign('error', 'Es wurde nicht alle erforderlichen Angaben gemacht.');
        $this->_tpl->assign('data', $data);
        $this->_tpl->assign('done', false);
      } else {
        $response = recaptcha_check_answer(
          $config['recaptcha']['private_key'],
          $_SERVER['REMOTE_ADDR'], 
          $_POST['recaptcha_challenge_field'],
          $_POST['recaptcha_response_field']
        );
       
        if($response != null && $response->is_valid) {
          // Send mail
          // mail(...)
          $this->_tpl->assign('done', true);
        } else {
          $this->_tpl->assign('error', 'Falscher Sicherheitscode. Bitte erneut versuchen!');
          $this->_tpl->assign('done', false);
        }
      }
    }
        
    
  }

}