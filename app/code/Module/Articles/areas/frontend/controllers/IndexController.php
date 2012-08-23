<?php
require_once 'Module/Articles/Controller/Action/Frontend.php';

class Articles_IndexController extends Module_Articles_Controller_Action_Frontend{

  function preDispatch() {
    $this->view->current_main_menu = 4;
    $this->view->gallery_path    = App::module('Addons')->getModel('Gallery')->get_gallery_base_path();
  }

  function listAnnouncementAction(){
    $this->view->announcement    = $this->_module->getModel('Article')
                                                 ->get_article_list(
                                                   $this->getRequest()->getParam( App::xlat('route_paginator_page') ),
                                                   $this->_module->getConfig('core','article_type_announcement_id')
                                                 );

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

  function listEventsAction(){
    $this->view->events          = $this->_module->getModel('Article')
                                                 ->get_article_list(
                                                   $this->getRequest()->getParam( App::xlat('route_paginator_page') ),
                                                   $this->_module->getConfig('core','article_type_event_id')
                                                   ,true
                                                 );

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }


  function readAnnouncementAction(){
    $this->read_article();
  }

  function readEventsAction(){
    $this->read_article();
  }

  private function read_article() {
    $article_seo         = $this->getRequest()->getParam('seo');
    $this->view->article = $this->_module->getModel('Article')->get_article( $article_seo );
    $this->view->addons  = $this->_module->getModel('Article')->get_article_addons( $this->view->article['article_id'] );

    App::header()->addLink(App::skin('/css/rate.css'),array("rel"=>"stylesheet", "type"=>"text/css", "media"=>"all"));
    App::header()->addCode("
        <script>
          var rate_id         = '". $this->view->article['article_id'] ."';
          var rating_on_error = '". App::xlat('RATING_on_error') ."';
        </script>");
    App::header()->addScript( App::url()->get('/rate.js','js') );

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs(  $this->getRequest()->getParam('action') , $this->view->article['title']  );
  }

  function readAction(){
    $this->read_article();
  }

  protected function get_breadcrumbs($action=null, $title=null ){
    switch ( $action ){
      case 'list-announcement':
              return array(
                array('title'=> App::xlat('BREADCRUM_announcement' ) )
              );
              break;
      case 'read-announcement':
              return array(
                array('title'=> App::xlat('BREADCRUM_announcement' ) , 'url' => App::base( rtrim(App::xlat('route_announcement'), "/") ) ),
                array('title'=> $title )
              );
      case 'list-events':
              return array(
                array('title'=> App::xlat('BREADCRUM_events' ) )
              );
              break;
      case 'read-events':
              return array(
                array('title'=> App::xlat('BREADCRUM_events'), 'url' => App::base( rtrim(App::xlat('route_events'),"/") ) ),
                array('title'=> $title )
              );
              break;
      case 'read':
        return array(
        array('title'=> $title )
        );
        break;
      default:
              return null;
              break;
    }

  }

}