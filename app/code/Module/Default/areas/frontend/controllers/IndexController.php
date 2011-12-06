<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class IndexController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){}

  function indexAction(){
    $this->designManager()->setCurrentLayout('intro');
    $this->view->current_main_menu = null;
    // $this->view->show_facebook_like_this_button = true;
  }

  function aboutUsAction(){
    $this->view->current_main_menu = 0;
    
		// Breadcrumbs
		$this->view->pageBreadcrumbs=array(
			array('title'=>App::xlat('BREADCRUM_about_us'))
		);
  }

  function doctrineAction(){
    $this->view->current_main_menu = 1;

		// Breadcrumbs
		$this->view->pageBreadcrumbs=array(
			array('title'=>App::xlat('BREADCRUM_doctrine'))
		);
  }

  function eventsAction(){
    $this->view->current_main_menu = 2;
    
		// Breadcrumbs
		$this->view->pageBreadcrumbs=array(
			array('title'=>App::xlat('BREADCRUM_events'))
		);
  }

  function radioAction(){
    $this->view->current_main_menu = 3;

		// Breadcrumbs
		$this->view->pageBreadcrumbs=array(
			array('title'=>App::xlat('BREADCRUM_radio'))
		);
  }

  function contactUsAction(){
    $this->view->current_main_menu = 4;

		// Breadcrumbs
		$this->view->pageBreadcrumbs=array(
			array('title'=>App::xlat('BREADCRUM_contact_us'))
		);
  }

  function privacyPolicyAction(){}

  function termsConditionsAction(){}

  function siteMapAction(){}

  function __call($function, $args){
    $desired_action          = $this->getRequest()->getActionName();
    $actions_for_this_locale = App::module('Core')->getModel('Actions')->get_translated_actions( $desired_action );

    if ( $actions_for_this_locale && array_key_exists($desired_action, $actions_for_this_locale) ){
      $action = $actions_for_this_locale[$desired_action]['action'];
      $view   = $actions_for_this_locale[$desired_action]['view'];
      call_user_func("self::{$action}Action");
      $this->_helper->getHelper('ViewRenderer')->setScriptAction($view);
    }

  }

}