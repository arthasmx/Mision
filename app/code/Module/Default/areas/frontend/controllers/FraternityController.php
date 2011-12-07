<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class FraternityController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){}

  function indexAction(){
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

  function fraternityAction(){

    $fraternities = explode( ",", App::getConfig('fraternities') );
    $gender       = $this->getRequest()->getParam('gender');
    if ( ! in_array( $gender, $fraternities ) ){
      $this->_module->exception(404);
    }

    $this->view->gender = $gender;
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->gender );
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