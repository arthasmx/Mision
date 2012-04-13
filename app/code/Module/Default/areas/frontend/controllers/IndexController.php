<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class IndexController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){}

  function indexAction(){
    $this->designManager()->setCurrentLayout('intro');
    $this->view->current_main_menu = null;
    $articles                      = App::module('Articles');
    $this->view->gallery_path      = App::module('Addons')->getModel('Gallery')->get_gallery_base_path();
  }

  function aboutUsAction(){
    $this->view->current_main_menu = 0;

    $this->view->aboutus         = App::module('Articles')->getModel('Article')->get_article( $this->getRequest()->getParam('action') );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_about' );
  }

  function doctrineAction(){
    $this->view->current_main_menu = 1;
    $this->view->doctrine_article  = App::module('Articles')->getModel('Article')->get_article( 'doctrina' );
    if( empty($this->view->doctrine_article) ){
      $this->_module->exception(404);
    }

    App::header()->addLink(App::skin('/css/tabs-accordion.css'),array(
        "rel"=>"stylesheet",
        "type"=>"text/css",
        "media"=>"all",
    ));
    App::header()->addScript( App::url()->get('/jquery.tools.min.js','js') );
    App::header()->addCode("
        <script type='text/javascript'>
        jQuery(document).ready(function(){
          $('#accordion').tabs(
            '#accordion div.pane',
            {tabs: 'h2', effect: 'slide', initialIndex: 0}
          );
        });
        </script>
        ");

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_doctrine' );
  }

  function multimediaAction(){
		$this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'FOOTER_menu_topic_multimedia' );
  }

  function ministeryAction(){
    $this->view->current_main_menu = 2;
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_ministery' );
  }
  
  function cellAction(){
    $this->view->current_main_menu = 3;
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_cell' );
  }

  function contactUsAction(){
    $this->view->current_main_menu = 5;
    $request = $this->getRequest();

    $form = $this->_module->getModel('Forms/Contact')->get();
    if ( $request->isPost() ){

      require_once('Xplora/Captcha.php');
      $captcha = new Xplora_Captcha();
      if ( ! $captcha->validate(@$_POST['captcha']) ) {
        $form->getElement('captcha')->getValidator('Custom')->addError("captchaWrongCode",App::xlat("ERROR_bad_captcha"));
      }

      if($form->isValid($_POST) ) {
        App::events()->dispatch('module_default_contacto',array("to"=>App::module('Email')->getConfig('core','frontend_contact'), "comment"=>$request->getParam('comment'), "name"=>$request->getParam('name'), "email"=>$request->getParam('email')));
        $this->view->message_sent = true;
        $form->reset();
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

  function joinUsAction(){
    $this->view->joinus          = App::module('Articles')->getModel('Article')->get_article( $this->getRequest()->getParam('action') );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_join_us' );
  }

  function projectsAction(){
    $this->view->projects        = App::module('Articles')->getModel('Article')->get_article( $this->getRequest()->getParam('action') );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( 'LINK_projects' );
  }

  function preachingAction(){
    App::header()->addScript(App::url()->get('/highslide.js','js'));
    App::header()->addScript(App::url()->get('/highslide.with.html.config.js','js'));
    App::header()->addLink(App::skin('/css/highslide.css'),array('rel'=>'stylesheet','type'=>'text/css'));
    App::header()->addCode("
        <script type='text/javascript'>
          hs.graphicsDir = '" . App::skin('/art/highslide/') . "';
          hs.outlineType = 'rounded-white';
          hs.outlineWhileAnimating = true;
        </script>
        ");

    $this->view->preaching        = App::module('Addons')->getModel('Audio')->get_preaching( $this->getRequest()->getParam( App::xlat('route_paginator_page') ) );
    $this->view->pageBreadcrumbs  = $this->get_breadcrumbs( 'LINK_preaching' );
  }

  function preachAction(){
    $this->designManager()->setCurrentLayout('ajax');
    $this->view->preach = App::module('Addons')->getModel('Audio')->get_preach( $this->getRequest()->getParam('id'), TRUE, TRUE );
  }

  function flexarAction(){
    $this->designManager()->setCurrentLayout('flexar');

    $request = $this->getRequest();
    $form    = $this->_module->getModel('Forms/Flexar')->get();
    if ( $request->isPost() ){

      require_once('Xplora/Captcha.php');
      $captcha = new Xplora_Captcha();
      if ( ! $captcha->validate(@$_POST['captcha']) ) {
        $form->getElement('captcha')->getValidator('Custom')->addError("captchaWrongCode",App::xlat("ERROR_bad_captcha"));
      }

      if($form->isValid($_POST) ) {
        App::events()->dispatch('module_default_flexar',array("to"=>App::module('Email')->getConfig('core','remitente_carboncopy_rcpt'), "comment"=>$request->getParam('comment'), "name"=>$request->getParam('name'), "email"=>$request->getParam('email')));
        $this->view->message_sent = true;
        $form->reset();
      }else{
        $form->populate($_POST);
      }

    }
    $this->view->form = $form;
  }


/* DOWNLOADS */

  function audioDownloadAction(){
    $file_path = $this->getRequest()->getParam('folder').DS.$this->getRequest()->getParam('year').DS.$this->getRequest()->getParam('month').DS.$this->getRequest()->getParam('file'); 
    App::module('Core')->getModel('Filesystem')->set_file($file_path)->force_to_download();
    exit;
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
      call_user_func( array($this, "{$action}Action") );
      $this->_helper->getHelper('ViewRenderer')->setScriptAction($view);
    }else{
      $this->_module->exception("Action Given Does Not Exist");
    }
  }

}