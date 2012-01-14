<?php
require_once 'Module/Articles/Controller/Action/Frontend.php';

class Articles_IndexController extends Module_Articles_Controller_Action_Frontend{

  function preDispatch() {}

  function listAnnouncementAction(){
    $this->view->articles        = $this->_module->getModel('Article')
                                                 ->get_article_list_by_type( 
                                                   $this->_module->getConfig('core','article_type_announcement_id'),
                                                   $this->getRequest()->getParam( App::xlat('route_paginator_page') ) 
                                                 );

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

  function listEventsAction(){
    $this->view->current_main_menu = 2;
    $this->view->events          = $this->_module->getModel('Article')
                                                 ->get_article_list_by_type( 
                                                   $this->_module->getConfig('core','article_type_event_id'),
                                                   $this->getRequest()->getParam( App::xlat('route_paginator_page') ) 
                                                 );

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }


  function readAnnouncementAction(){
    $this->read_article();
  }

  function readEventsAction(){
    $this->view->current_main_menu = 2;
    $this->read_article();
  }

  private function read_article() {
    $article_seo         = $this->getRequest()->getParam('seo');
    $this->view->article = $this->_module->getModel('Article')->get_article( $article_seo );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs(  $this->getRequest()->getParam('action') , $this->view->article['title']  );

    /*
     * request params
Array
(
    [seo] => cena-navidena
    [module] => Articles
    [controller] => index
    [section] => announcement
    [action] => read-announcement
    [controller_prefix] => 
)
     */
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
      default:
              return null;
              break;
    }

  }

}