<?php
require_once 'Core/Controller/Block.php';
class Addons_Bible_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function searchAction(){
    App::header()->addScript(App::url()->get('/bible.js','js'));
    App::header()->addCode("
        <script type='text/javascript'>
          var bad_request  = '". App::xlat('EXC_bad_request') ."';
          var ajax_loading = '". App::xlat('AJAX_loading') ."';
          var empty_search = '". App::xlat('AJAX_empty_search') ."';
          var choose_an_option = '". App::xlat('AJAX_choose_an_option') ."';
          var load_books_url = '". App::base() . App::xlat('route_bible') ."load-books';
        </script>
    ");

    $this->view->form = $this->_module->getModel('Forms/Bible')->get( $this->getParam("form_type"), $this->getParam("reset") );
    $view_template    = ( $this->getParam("form_type") === "simple" ) ? "search-intro" : "search" ;
    $this->setScriptAction( $view_template );
  }

  function phraseAction(){
    $this->view->phrase = $this->_module->getModel("Bible")->get_phrase();
  }

  function booksAction(){
    $this->view->current_book_id = $this->getParam('book_id');
    $this->view->books           = $this->_module->getModel("Bible")->get_books();
  }

  function bookDetailAction(){
    $this->view->details         = $this->getParam('details');
    $chapter                     = $this->getParam('chapter');
    $this->view->chapter_details = empty($chapter) ? false : $this->_module->getModel('Bible')->get_verses_in_chapter( $this->view->details['seo'], $this->getParam('chapter')  );
  }

  function chaptersAction(){
    $book_seo                    = $this->getParam('book_seo');
    $this->view->chapters        = $this->_module->getModel('Bible')->get_chapters( $book_seo );
    $this->view->current_chapter = $this->getParam('chapter');
    $this->view->current_book    = $book_seo;
  }

  function chaptersPaginatorAction(){
    $this->view->chapters        = $this->_module->getModel('Bible')->get_chapters_for_pagination( $this->getParam('seo'), $this->getParam('chapter') );
    $this->view->book_seo        = $this->getParam('seo');
    $this->view->current_chapter = $this->getParam('chapter');
    $this->view->chapters_total  = $this->getParam('chapters_total');
  }

  function versesPaginatorAction(){
    $this->view->verses        = $this->_module->getModel('Bible')->get_verses_for_pagination( $this->getParam('book'), $this->getParam('chapter'), $this->getParam('verse') );
    $this->view->book            = $this->getParam('book');
    $this->view->current_chapter = $this->getParam('chapter');
    $this->view->current_verse   = $this->getParam('verse');
    $this->view->verses_total  = $this->getParam('verses_total');
  }

}