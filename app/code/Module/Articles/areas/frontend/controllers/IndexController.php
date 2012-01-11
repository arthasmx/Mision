<?php
require_once 'Module/Articles/Controller/Action/Frontend.php';

class Articles_IndexController extends Module_Articles_Controller_Action_Frontend{

  function preDispatch() {}

  function listAnnouncementAction(){
    $core_abstract = $this->_module->getModel('Sorterfilter')
                                   ->announcement_sort_rules()
                                   ->announcement_filter_rules();

    $this->view->datasorter = $core_abstract->datasorter_to_render();
    $this->view->datafilter = $core_abstract->datafilter_to_render();

    $this->view->articles   = $this->_module->getModel('Article')
                                            ->get_announcement_list( $core_abstract );

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

  function listEventsAction(){
    $core_abstract = $this->_module->getModel('Sorterfilter')
                                   ->events_sort_rules()
                                   ->events_filter_rules();

    $this->view->datasorter = $core_abstract->datasorter_to_render();
    $this->view->datafilter = $core_abstract->datafilter_to_render();

    $this->view->articles   = $this->_module->getModel('Article')
                                            ->get_events_list( $core_abstract );

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }


  function readAnnouncementAction(){
    $this->read_article();
  }

  function readEventsAction(){
    $this->view->current_main_menu = 2;
    $this->read_article();
  }

  private function read_article() {
    $article_seo         = $this->getRequest()->getParam('seo');
    $this->view->article = $this->_module->getModel('Article')->get_article( $article_seo );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs(  $this->getRequest()->getParam('action') , $this->view->article['title']  );

    /*
     * request params
Array
(
    [seo] => cena-navidena
    [module] => Articles
    [controller] => index
    [section] => announcement
    [action] => read-announcement
    [controller_prefix] => 
)
     */
  }



  protected function get_breadcrumbs($action=null, $title=null ){
    switch ( $action ){
      case 'list-announcement':
              return array(
                array('title'=> App::xlat('BREADCRUM_announcement' ) )
              );
              break;
      case 'read-announcement':
              return array(
                array('title'=> App::xlat('BREADCRUM_announcement' ) , 'url' => App::base( rtrim(App::xlat('route_announcement'), "/") ) ),
                array('title'=> $title )
              );
      case 'list-events':
              return array(
                array('title'=> App::xlat('BREADCRUM_events' ) )
              );
              break;
      case 'read-events':
              return array(
                array('title'=> App::xlat('BREADCRUM_events'), 'url' => App::base( rtrim(App::xlat('route_events'),"/") ) ),
                array('title'=> $title )
              );
              break;
      default:
              return null;
              break;
    }

  }

}