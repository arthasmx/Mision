<?php
require_once 'Core/Controller/Block.php';
class Articles_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function sliderAction(){
    // $this->view->gallery_path = App::module('Addons')->getModel('Gallery')->get_gallery_base_path();
    $this->view->articles = App::module('Articles')->getModel('Article')->get_articles_for_content_slider();

    if( ! empty($this->view->articles ) ){
      //App::module('Core')->getModel('Libraries')->galleria_io();
      //$this->setScriptAction( "slider-galleria.io" );
      
      App::module('Core')->getModel('Libraries')->responsive_gallery();
      $this->setScriptAction( "slider" );
    }

  }

  function welcomeAction(){}



  function latestAction(){
    $this->view->latest = $this->_module->getModel('Article')->latest();
    App::module('Core')->getModel('Libraries')->twitter_bootstrap_slider_autoplay('#latest-articles-carousel');
  }

  function promoteAction(){
    $this->view->promote  = $this->_module->getModel('Article')->get_article_basic_data( $this->getParam('seo') );
  }

  function promoteDescribedAction(){
    $this->view->promote  = $this->_module->getModel('Article')->get_article_basic_data( $this->getParam('seo') );
  }



  function carouselPromoteAction(){
    $this->view->promote = $this->_module->getModel('Article')->latest( App::xlat('articulos') );
    App::module('Core')->getModel('Libraries')->twitter_bootstrap_slider_autoplay('#articles-carousel');
  }

  function carouselPromoteDescribedAction(){
    $this->view->promote = $this->_module->getModel('Article')->latest( App::xlat('articulos') );
    App::module('Core')->getModel('Libraries')->twitter_bootstrap_slider_autoplay('#articles-described-carousel');
  }



  function radioAction(){
    $this->view->article = $this->_module->getModel('Article')->get_article_basic_data( 'radio' );
  }

}