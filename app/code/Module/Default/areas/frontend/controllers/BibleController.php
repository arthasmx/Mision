<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class BibleController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){
    $this->view->current_main_menu = 5;
  }

  function indexAction(){
    $this->view->books = App::module('Addons')->getModel('Bible')->get_books();
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

  function bookAction(){
    $this->view->book            = App::module('Addons')->getModel('Bible')->get_book_details( $this->getRequest()->getParam('book') );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->book['book'] );
  }

  function chapterAction(){
    $this->view->book      = $this->getRequest()->getParam('book');
    $this->view->chapter   = $this->getRequest()->getParam('chapter');
    $this->view->verses    = App::module('Addons')->getModel('Bible')->get_verses( $this->view->book, $this->view->chapter );
    $this->view->book_name = App::module('Addons')->getModel('Bible')->get_book_name( $this->view->book );

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->book_name, $this->view->book, $this->view->chapter );
  }

  function verseAction(){
    $this->view->book     = $this->getRequest()->getParam('book');
    $this->view->chapter  = $this->getRequest()->getParam('chapter');
    $verse                = $this->getRequest()->getParam('verse');

    $this->view->verse = App::module('Addons')->getModel('Bible')->get_verse( $this->view->book, $this->view->chapter, $verse );
    if( empty($this->view->verse) ){
      $this->_module->exception(404);
    }

    $book_name = App::module('Addons')->getModel('Bible')->get_book_name( $this->view->book );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $book_name, $this->view->book, $this->view->chapter, $verse );
  }

  protected function get_breadcrumbs( $action = null, $book_name=null, $book=null, $chapter=null, $verse=null ){

    $route        = App::xlat('route_bible');
    $trimed_route = rtrim($route, '/');

    switch ( $action ){
      case 'index':
              return array(
                array('title'=>App::xlat('BREADCRUM_bible'))
              );
              break;
      case 'book':
              return array(
                array('title'=> App::xlat('BREADCRUM_bible')        , 'url' => App::base( $trimed_route ) ),
                array('title'=> $book_name )
              );
              break;
      case 'chapter':
              return array(
                array('title'=> App::xlat('BREADCRUM_bible')        , 'url' => App::base( $trimed_route ) ),
                array('title'=> $book_name                          , 'url' => App::base( $route . $book ) ),
                array('title'=> App::xlat('BIBLE_chapter') . ' ' . $chapter )
              );
              break;
      case 'verse':
              return array(
                array('title'=> App::xlat('BREADCRUM_bible')        , 'url' => App::base( $trimed_route ) ),
                array('title'=> $book_name                          , 'url' => App::base( $route . $book ) ),
                array('title'=> App::xlat('BIBLE_chapter') . ' ' . $chapter , 'url' => App::base( $route . $book . '/' . $chapter ) ),
                array('title'=> App::xlat('BIBLE_verse') . ' ' . $verse )
              );
              break;
      default:
              return null;
              break;
    }

  }

}