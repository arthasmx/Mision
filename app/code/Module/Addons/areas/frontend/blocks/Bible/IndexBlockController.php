<?php
require_once 'Core/Controller/Block.php';
class Addons_Bible_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function phraseAction(){
    $this->view->phrase = $this->_module->getModel("bible")->get_phrase();
  }

  function bookDetailAction(){
    $this->view->book_details = App::module('Addons')->getModel('Bible')->get_book_details( $this->getParam('seo') );
  }

}