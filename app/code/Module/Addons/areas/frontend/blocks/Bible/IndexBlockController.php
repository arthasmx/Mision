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
    $this->view->details = $this->getParam('details');
  }

  function chaptersAction(){
    $this->view->chapters        = $this->_module->getModel('Bible')->get_chapters_block( $this->getParam('seo'), $this->getParam('chapter') );
    $this->view->book_seo        = $this->getParam('seo');
    $this->view->current_chapter = $this->getParam('chapter');
    $this->view->chapters_total  = $this->getParam('chapters_total');
  }

}