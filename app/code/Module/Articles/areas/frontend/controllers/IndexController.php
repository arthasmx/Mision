<?php
require_once 'Module/Articles/Controller/Action/Frontend.php';

class Articles_IndexController extends Module_Articles_Controller_Action_Frontend{

  function preDispatch() {}

  /*
   * FALTA:
  * x Paginacion
  * x Ordenamiento
  * - Filtros
  * * Haciendo este bien, lo podre utilizar para todos los demas listados!
  */
  function listAction(){
    $section        = $this->getRequest()->getParam('section');
    $paginator_page = $this->getRequest()->getParam( App::xlat('route_paginator_page') );

    $datasorter = $this->_module->getModel('Datasorter')->sort_get_list();
    $this->view->datasorter = App::module('Core')->getModel('Abstract')
                                                 ->setDatasorter($datasorter)
                                                 ->datasorter_prepare();

    $this->view->datafilter = $this->_module->getModel('Datafilter')
                                            ->setDatafilter_render_style( App::getConfig('datafilter_uses_render_style') )
                                            ->filter_get_list();

    $where = array();
    if ( $this->view->datafilter->isActive() ) {
      require_once('Xplora/Datafilter/Sql.php');
      foreach ($this->view->datafilter->getFields() as $id=>$field) {
        if (true===$field->getActive() && strtolower($field->gettype())!='attribute') {
          $where[]=Xplora_Datafilter_Sql::getFieldCondition($field);
        }
      }
    }

    $this->view->articles   = $this->_module->getModel('Article')
                                   ->setPaginator_page($paginator_page)
                                   ->setDatasorter( $datasorter )
                                   ->setDatafilter( $this->view->datafilter )
                                   ->get_list($section, "seo");

    if( empty($this->view->articles) ){
      $this->_module->exception(404);
    }

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action') );
  }

/*
 * FALTA:
 * - Sacar el texto principal del articulo
 * - Crear la vista(s) para mostrar dicho articulo
 * * de momento solamente obtenemos los datos basicos del articulo, pero falta el contenido
 */
  function readAction() {
    $article_seo         = $this->getRequest()->getParam('seo');
    $this->view->article = $this->_module->getModel('Article')->get_article( $article_seo );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('section'), $this->view->article['title']  );
  }

  function eventsAction() {
    $this->view->current_main_menu = 2;

    $article_seo         = $this->getRequest()->getParam('seo');
    $this->view->article = $this->_module->getModel('Article')->get_article( $article_seo );
    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->article['title']  );
  }

  protected function get_breadcrumbs( $action = null, $title=null ){
    $base_breadcrumb = App::xlat('BREADCRUM_' . $action );
    switch ( $action ){
      case 'list':
              return array(
                array('title'=> $this->getRequest()->getParam('section') )
              );
              break;

      case 'read':
      case 'anuncios':
      case 'announcement':
      case 'events':
              return array(
              array('title'=> $base_breadcrumb , 'url' => App::base( App::xlat('route_' . $action ) ) ),
              array('title'=> $title )
              );
              break;
      default:
              return null;
              break;
    }

  }

}