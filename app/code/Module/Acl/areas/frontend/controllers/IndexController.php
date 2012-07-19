<?php
require_once 'Module/Acl/Controller/Action/Frontend.php';

class Acl_IndexController extends Module_Acl_Controller_Action_Frontend {

  function preDispatch(){}

  function loginAction(){
    $request           = $this->getRequest();
    $form              = $this->_module->getModel('Acl/Forms/Login')->get();
    $this->view->login = true;

    if ( $request->isPost() ){
      if( $form->isValid($_POST) ){
        $this->_module->getModel('Acl')->login( $this->getRequest()->getParam('user'), $this->getRequest()->getParam('password'));
      }
      $form->populate($_POST);
    }
    $this->view->form = $form;
  }


  protected function get_breadcrumbs( $breadcrumb = null ){
    if( empty($breadcrumb)){
      return null;
    }

    return array( array('title'=> App::xlat($breadcrumb) ) );
  }

}