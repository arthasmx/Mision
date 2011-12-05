<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class FraternityController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){}

  function indexAction(){

  }

  function fraternityAction(){

    $fraternities = explode( ",", App::getConfig('fraternities') );
    $gender       = $this->getRequest()->getParam('gender');
    if ( ! in_array( $gender, $fraternities ) ){
      $this->_module->exception(404);
    }

    $this->view->gender = $gender;

    // Breadcrumbs
    $this->view->pageBreadcrumbs=array(
      array('title'=>App::xlat('BREADCRUM_about_us'))
    );

  }


}