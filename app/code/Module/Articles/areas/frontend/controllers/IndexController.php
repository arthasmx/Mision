<?php
require_once 'Module/Articles/Controller/Action/Frontend.php';

class Articles_IndexController extends Module_Articles_Controller_Action_Frontend{

  function preDispatch() {
    $this->view->current_main_menu = 4;
  }

  function readAction(){
    $this->read_article();
  }

  private function read_article() {
    $this->view->article = $this->_module->getModel('Article')->read_full_article( $this->getRequest()->getParam('seo'),true,true );
    $this->view->addons  = $this->_module->getModel('Article')->get_article_addons( $this->view->article['article_id'], true );
    $this->view->folders = $this->_module->getModel('Article')->set_article_folders($this->view->article['article_id'],$this->view->article['created'] );

    App::module('Core')->getModel('Libraries')->addons_dropdown_menu();
    App::module('Core')->getModel('Libraries')->youtube_video_player();
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs(  $this->getRequest()->getParam('action') , $this->view->article['title']  );
  }


  protected function get_breadcrumbs($action=null, $title=null ){
    switch ( $action ){
      case 'read':
        return array(
        array('title'=> $title )
        );
        break;
      default:
              return null;
              break;
    }

  }

}