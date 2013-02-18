<?php
require_once 'Module/Default/Controller/Action/Frontend.php';

class IndexController extends Module_Default_Controller_Action_Frontend {

  function preDispatch(){}

  function indexAction(){
    $this->designManager()->setCurrentLayout('intro');
    $this->view->current_main_menu = null;
  }

  function aboutUsAction(){
    $this->view->current_menu    = $this->getRequest()->getParam('action');
    $this->view->article         = App::module('Articles')->getModel('Article')->read_full_article( $this->getRequest()->getParam('action'),true,true );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_about_us'), 'icon'=>'icon-group' );
  }

  function doctrineAction(){
    $this->view->current_menu    = 'doctrina';
    App::module('Core')->getModel('Libraries')->twitter_bootstrap_slider('#doctrine-carousel');

    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_doctrine'), 'icon'=>'icon-briefcase' );
  }

  function ministeryAction(){
    $this->view->current_main_menu = 2;
    $this->view->article           = App::module('Articles')->getModel('Article')->get_article( 'ministerios' );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_ministeries'), 'icon'=>'icon-briefcase' );
  }

  function cellAction(){
    $this->view->current_menu = 'celulas';
    $this->view->cells        = App::module('Addons')->getModel('Cells')->cells(true,true);
    $this->view->sectors      = App::module('Addons')->getModel('Cells')->sectors(true,true);
    $this->view->zones        = App::module('Addons')->getModel('Cells')->zones(true,true);

    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_cell'), 'icon'=>'icon-group' );
  }

  function contactUsAction(){
    $libraries = App::module('Core')->getModel('Libraries');
    $libraries->google_map("contact-map");
    $libraries->contact();
    $libraries->block_ui();

    $this->view->current_menu    = $this->getRequest()->getParam('action');
    $this->view->form            = $this->_module->getModel('Forms/Contact')->get();
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_contact'), 'icon'=>'icon-envelope' );
  }

  function captchaContactRefreshAction(){
    $this->designManager()->setCurrentLayout('ajax');
    $form    = $this->_module->getModel('Forms/Contact')->get();
    $captcha = $form->getElement('captcha')->getCaptcha();
    $data    = array();

    $data['id']  = $captcha->generate();
    $data['src'] = $captcha->getImgUrl() .
    $captcha->getId() .
    $captcha->getSuffix();

    $this->_helper->json($data);
    exit;
  }

  function contactAction(){
    $this->designManager()->setCurrentLayout('ajax');
    $request = $this->getRequest();
    $form    = $this->_module->getModel('Forms/Contact')->get();

    if ( $request->isPost() ){

      if( $form->isValid($_POST) ){
        App::events()->dispatch('module_default_contacto',array("to"=>App::module('Email')->getConfig('core','frontend_contact'), "comment"=>$request->getParam('comments'), "name"=>$request->getParam('name'), "email"=>$request->getParam('email')));
        $answer = date('Y');
      }else{
        $answer = App::module('Core')->getModel('Form')->get_json_error_fields($form);
      }
    }
    echo $answer;
    exit;
  }



  function siteMapAction(){
    $this->view->article         = App::module('Articles')->getModel('Article')->read_full_article( $this->getRequest()->getParam('action'),true,true );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_map'), 'icon'=>'icon-sitemap' );
  }

  function siteRequirementsAction(){
    $this->view->article         = App::module('Articles')->getModel('Article')->read_full_article( $this->getRequest()->getParam('action'),true,true );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_requirements'), 'icon'=>'icon-cog' );
  }

  function termsConditionsAction(){
    $this->view->article         = App::module('Articles')->getModel('Article')->read_full_article( $this->getRequest()->getParam('action'),true,true );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_terms'), 'icon'=>'icon-edit' );
  }



  function strategiesAction(){
//    $this->view->article         = App::module('Articles')->getModel('Article')->read_full_article( $this->getRequest()->getParam('action'),true,true );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_strategies'), 'icon'=>'icon-cogs' );
  }

  function calendarAction(){
//    $this->view->article         = App::module('Articles')->getModel('Article')->read_full_article( $this->getRequest()->getParam('action'),true,true );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_activities'), 'icon'=>'icon-calendar' );
  }

  function joinUsAction(){
//    $this->view->article         = App::module('Articles')->getModel('Article')->read_full_article( $this->getRequest()->getParam('action'),true,true );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_join'), 'icon'=>'icon-fire' );
  }

  function preachingAction(){
    $this->view->preaching       = App::module('Addons')->getModel('Audio')->get_preaching( $this->getRequest()->getParam( App::xlat('route_paginator_page') ) );
    $this->view->pageBreadcrumbs = array('title'=> App::xlat('breadcrumb_preaching'), 'icon'=>'icon-volume-up' );
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

  function downloadArticleAddonAction(){
    $params  = $this->getRequest()->getParams();
    $fileSys = App::module('Core')->getModel('Filesystem');
    if( empty($params['date']) || empty($params['id']) || empty($params['type']) || empty($params['reference']) ){
      App::module('Core')->exception( App::xlat('EXC_article_wasnt_found_') );
    }

    $folder = App::module('Articles')->getConfig('core','folders'); 
    $file   = $folder['articles'] .DS . App::module('Core')->getModel('Dates')->toDate(10, date("Y-m-d", $params['date'])) .DS. $params['id'] .DS. $params['type'] .DS. $params['reference'];

    // Download!
    $fileSys->set_file($file)->force_to_download();
    exit;
  }

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