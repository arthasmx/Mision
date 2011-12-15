<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class DoctrineController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){
    $this->view->current_main_menu = 1;
  }

  /*
   * @todo: Estoy sacando el articulo hardcoded, lo cual no es correcto.
   */
  function doctrineAction(){
    $this->view->doctrine_article = App::module('Articles')->getModel('Article')->get_article( 'doctrina' );
    if( empty($this->view->doctrine_article) ){
      $this->_module->exception(404);
    }

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

  function detailAction(){
    $this->view->current_main_menu = 1;

    $this->view->doctrine_detail = App::module('Articles')->getModel('Article')->get_article( $this->getRequest()->getParam('seo') );
    if( empty($this->view->doctrine_detail) ){
      $this->_module->exception(404);
    }

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->doctrine_detail['title'] );
  }

  protected function get_breadcrumbs( $action = null, $title = null ){

	$trimed_route = rtrim( App::xlat('route_doctrine'), "/" );
    switch ( $action ){
      case 'doctrine':
              return array(
                array('title'=> App::xlat('LINK_doctrine') )
              );
              break;
      case 'detail':
              return array(
                array('title'=> App::xlat('LINK_doctrine')  , 'url' => App::base( $trimed_route ) ),
                array('title'=> $title)
              );
              break;
      default:
              return null;
              break;
    }

  }


}