<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class BibleController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){
    $this->view->current_main_menu = 5;
  }

  function indexAction(){
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

  function bookAction(){
    $this->view->book = App::module('Addons')->getModel('Bible')->get_book( $this->getRequest()->getParam('book') );
    if( empty($this->view->book) ){
      $this->_module->exception(404);
    }


    /*
     x total de capitulos
     x total de versiculos
     - listado de los capitulos para con 1 click llevarnos ahi
     */

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->book['details']['book'] );
  }

  function capAction(){
    $book = $this->getRequest()->getParam('book');
    $cap  = $this->getRequest()->getParam('cap');

    $this->view->cap = App::module('Addons')->getModel('Bible')->get_cap( $book, $cap );
    if( empty($this->view->cap) ){
      $this->_module->exception(404);
    }    

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->cap[0]['book'], $book, $cap );
  }

  function verAction(){
    $book = $this->getRequest()->getParam('book');
    $cap  = $this->getRequest()->getParam('cap');
    $ver  = $this->getRequest()->getParam('ver');

    $this->view->ver = App::module('Addons')->getModel('Bible')->get_ver( $book, $cap, $ver );
    if( empty($this->view->ver) ){
      $this->_module->exception(404);
    }

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->ver['book'], $book, $cap, $ver );
  }

  protected function get_breadcrumbs( $action = null, $book_name=null, $book=null, $cap=null, $ver=null ){

    switch ( $action ){
      case 'index':
              return array(
                array('title'=>App::xlat('BREADCRUM_bible'))
              );
              break;
      case 'book':
              return array(
                array('title'=> App::xlat('BREADCRUM_bible')        , 'url' => App::base( App::xlat('route_bible') ) ),
                array('title'=> $book_name )
              );
              break;
      case 'cap':
              return array(
                array('title'=> App::xlat('BREADCRUM_bible')        , 'url' => App::base( App::xlat('route_bible') ) ),
                array('title'=> $book_name                          , 'url' => App::base( App::xlat('route_bible') . $book ) ),
                array('title'=> App::xlat('BIBLE_cap') . ' ' . $cap )
              );
              break;
      case 'ver':
              return array(
                array('title'=> App::xlat('BREADCRUM_bible')        , 'url' => App::base( App::xlat('route_bible') ) ),
                array('title'=> $book_name                          , 'url' => App::base( App::xlat('route_bible') . $book ) ),
                array('title'=> App::xlat('BIBLE_cap') . ' ' . $cap , 'url' => App::base( App::xlat('route_bible') . $book . '/' . $cap ) ),
                array('title'=> App::xlat('BIBLE_ver') . ' ' . $ver )
              );
              break;
      default:
              return null;
              break;
    }

  }

}