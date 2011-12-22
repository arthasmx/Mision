<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class FraternityController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){
    $this->view->current_main_menu = 3;
  }

  function indexAction(){
    $this->view->fraternities = App::module('Articles')->getModel('Article')->get_article( App::xlat('LINK_fraternities') );
    if( empty($this->view->fraternities) ){
      $this->_module->exception(404);
    }

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

  function fraternityAction(){
    $gender = $this->getRequest()->getParam('gender');
    $this->view->fraternity = App::module('Articles')->getModel('Article')->get_article( $gender );
    if( empty($this->view->fraternity) ){
      $this->_module->exception(404);
    }

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $gender );
  }

  protected function get_breadcrumbs( $action = null, $gender=null ){

	$trimed_route = rtrim( App::xlat('route_fraternities'), "/" );
    switch ( $action ){
      case 'index':
              return array(
                array('title'=> App::xlat('BREADCRUMBS_fraternities') )
              );
              break;
      case 'fraternity':
              return array(
                array('title'=> App::xlat('BREADCRUMBS_fraternities')  , 'url' => App::base( $trimed_route ) ),
                array('title'=> ($gender==="ninos")?'niños':$gender )
              );
              break;
      default:
              return null;
              break;
    }

  }


}