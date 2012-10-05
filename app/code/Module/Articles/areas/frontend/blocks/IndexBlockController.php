<?php
require_once 'Core/Controller/Block.php';
class Articles_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function sliderAction(){
    $this->view->gallery_path    = App::module('Addons')->getModel('Gallery')->get_gallery_base_path();
    $this->view->coming_next     = App::module('Articles')->getModel('Article')->get_articles_for_content_slider();

    if( ! empty($this->view->coming_next) ){
      App::module('Core')->getModel('Libraries')->rhino_slider();
    }

  }


  function promoteAction(){
    $width              = $this->getParam('width');
    $height             = $this->getParam('height');
    $limit              = $this->getParam('limit');
    $this->view->width  = empty($width)  ? $this->_module->getConfig('core','promote_block_width')  : $this->getParam('width');
    $this->view->height = empty($height) ? $this->_module->getConfig('core','promote_block_height') : $this->getParam('height');
    $limit              = empty($limit)  ? $this->_module->getConfig('core','promote_block_limit')  : $limit;
    $this->view->gallery_path = App::module('Addons')->getModel('Gallery')->get_gallery_base_path();

    $this->view->promotions = App::module('Articles')->getModel('Article')->get_articles_for_content_slider(null, $limit );

    if( ! empty($this->view->promotions) ){
      App::module('Core')->getModel('Libraries')->articles_promotion();
    }
  }

  function shepherdWelcomeAction(){
    $this->view->article = $this->_module->getModel('Article')->read_full_article( App::xlat('shepherd_welcome_article_details_id') );
  }

  function radioAction(){
    $this->view->article = $this->_module->getModel('Article')->get_article_basic_data( 'radio' );
  }

  function promoteArticleAction(){
    $this->view->article = $this->_module->getModel('Article')->get_article_basic_data( $this->getParam('article') );
    $this->view->type    = $this->getParam('type');

    $width  = $this->getParam('width');
    $height = $this->getParam('height');
    $this->view->width   = empty($width)  ? '300' : $width;
    $this->view->height  = empty($height) ? '250' : $height;
  }

}