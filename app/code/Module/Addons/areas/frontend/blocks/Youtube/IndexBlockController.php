<?php
require_once 'Core/Controller/Block.php';
class Addons_Youtube_IndexBlockController extends Core_Controller_Block {

  function showVideoAction(){
    $this->view->video    = $this->_module->getModel('Youtube')->get_video( $this->getParam('id') );
    $this->view->title    = $this->getParam('title');
    $this->view->subtitle = $this->getParam('subtitle');
  }

  function showUserVideosAction(){
    $this->view->videos = $this->_module->getModel('Youtube')->get_user_videos( $this->getParam('user') );
  }

}