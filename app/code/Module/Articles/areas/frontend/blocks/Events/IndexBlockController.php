<?php
require_once 'Core/Controller/Block.php';
class Articles_Events_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function latestAction(){
    $this->view->latest = $this->_module->getModel('Article')->latest( 'eventos' );
    App::module('Core')->getModel('Libraries')->twitter_bootstrap_slider_autoplay('#latest-events-carousel');
  }

  function promoteAction(){
    $this->view->promote  = $this->_module->getModel('Article')->get_article_basic_data( $this->getParam('seo') );
  }

  function promoteDescribedAction(){
    $this->view->promote  = $this->_module->getModel('Article')->get_article_basic_data( $this->getParam('seo') );
  }



  function carouselPromoteAction(){
    $this->view->promote = $this->_module->getModel('Article')->latest( App::xlat('eventos') );
    App::module('Core')->getModel('Libraries')->twitter_bootstrap_slider_autoplay('#events-carousel');
  }

  function carouselPromoteDescribedAction(){
    $this->view->promote = $this->_module->getModel('Article')->latest( App::xlat('eventos') );
    App::module('Core')->getModel('Libraries')->twitter_bootstrap_slider_autoplay('#events-described-carousel');
  }

}