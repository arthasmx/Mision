<?php
require_once 'Module/User/Controller/Action/Admin.php';
class IndexController extends Module_User_Controller_Action_Admin {

  function preDispatch(){
    $this->view->current_menu = array('menu'=>2,'sub'=>3); // initial menu
  }

  function indexAction(){
    $this->view->user_data = App::module("Acl")->getModel('acl')->get_logged_user_data();
  }

  function logoutAction(){
    App::module('Acl')->getModel('Acl')->logout();
  }


  protected function get_breadcrumbs( $breadcrumb = null ){
    if( empty($breadcrumb)){
      return null;
    }

    return array( array('title'=> App::xlat($breadcrumb) ) );
  }

}