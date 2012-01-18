<?php
require_once 'Core/Controller/Block.php';
class Addons_Bible_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function phraseAction(){
    $this->view->phrase = $this->_module->getModel("Bible")->get_phrase();
  }

  function booksAction(){
    $this->view->current_book_id = $this->getParam('book_id');
    $this->view->books           = $this->_module->getModel("Bible")->get_books();
  }

  function bookDetailAction(){
    $this->view->book_details = App::module('Addons')->getModel('Bible')->get_book_details( $this->getParam('seo') );
  }

}