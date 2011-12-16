<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class IndexController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){}

  function indexAction(){
    $this->designManager()->setCurrentLayout('intro');
    $this->view->current_main_menu = null;
  }

  function aboutUsAction(){
    $this->view->current_main_menu = 0;

		$this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_about' );
  }
 
  function eventsAction(){
    $this->view->current_main_menu = 2;
    
		$this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_events' );
  }

  function multimediaAction(){
    $this->view->current_main_menu = 3;

		$this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_multimedia' );
  }

  function contactUsAction(){
    $this->view->current_main_menu = 4;

    $form = $this->_module->getModel('Forms/Contact')->get();
    if ( $this->getRequest()->isPost() ){

      require_once('Xplora/Captcha.php');
      $captcha = new Xplora_Captcha();
      if ( ! $captcha->validate(@$_POST['captcha']) ) {
        $form->getElement('captcha')->getValidator('Custom')->addError("captchaWrongCode",App::xlat("ERROR_bad_captcha"));
      }

      if($form->isValid($_POST) ) {
        App::events()->dispatch('module_default_contacto',array("to"=>App::module('Email')->getConfig('core','frontend_contact'), "comment"=>@$_POST['comment'], "name"=>@$_POST['name'], "email"=>@$_POST['email']));
        App::module('Core')->getModel('Flashmsg')->success(App::xlat('CONTACT_message_sent'));
        Header("Location: " . App::base());
        exit;
      }else{
        $form->populate($_POST);
      }

    }
    $this->view->form = $form;

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'BREADCRUM_contact_us' );
  }

  function siteRequirementsAction(){
    $this->view->requirements    = App::module('Articles')->getModel('Article')->get_article( $this->getRequest()->getParam('action') );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( App::xlat('FOOTER_link_site_requirements') );
  }

  function siteMapAction(){
    $this->view->sitemap         = App::module('Articles')->getModel('Article')->get_article( $this->getRequest()->getParam('action') );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'FOOTER_link_sitemap' );
  }

  function privacyPolicyAction(){
    $this->view->privacy         = App::module('Articles')->getModel('Article')->get_article( $this->getRequest()->getParam('action') );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'FOOTER_link_privacy_policy' );
  }

  function termsConditionsAction(){
    $this->view->conditions      = App::module('Articles')->getModel('Article')->get_article( $this->getRequest()->getParam('action') );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'FOOTER_link_terms_conditions' );
  }

  protected function get_breadcrumbs( $breadcrumb = null ){
    if( empty($breadcrumb)){
      return null;
    }

    return array( array('title'=> App::xlat($breadcrumb) ) );
  }

  function __call($function, $args){
    $desired_action          = $this->getRequest()->getActionName();
    $actions_for_this_locale = App::module('Core')->getModel('Actions')->get_translated_actions( $desired_action );

    if ( $actions_for_this_locale && array_key_exists($desired_action, $actions_for_this_locale) ){
      $action = $actions_for_this_locale[$desired_action]['action'];
      $view   = $actions_for_this_locale[$desired_action]['view'];
      call_user_func("self::{$action}Action");
      $this->_helper->getHelper('ViewRenderer')->setScriptAction($view);
    }else{
      $this->_module->exception("Action Given Does Not Exist");
    }
  }

}