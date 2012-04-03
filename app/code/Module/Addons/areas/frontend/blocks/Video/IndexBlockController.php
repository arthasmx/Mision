<?php
require_once 'Core/Controller/Block.php';
class Addons_Video_IndexBlockController extends Core_Controller_Block {

  function liveAction(){
    $this->view->title    = $this->getParam('title');
    $this->view->subtitle = $this->getParam('subtitle');
  }

}