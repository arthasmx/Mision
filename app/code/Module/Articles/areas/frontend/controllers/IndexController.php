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
  function announcementAction(){
    $article_seo    = $this->getRequest()->getParam('seo');
    $paginator_page = $this->getRequest()->getParam( App::xlat('route_paginator_page') );

    $datasorter = $this->_module->getModel('Datasorter')->sort_get_articles_listing_by_article_type();
    $this->view->datasorter = App::module('Core')->getModel('Abstract')
                                                 ->setDatasorter($datasorter)
                                                 ->datasorter_prepare();

    $this->view->datafilter = $this->_module->getModel('Datafilter')
                                   ->setDatafilter_render_style( App::getConfig('datafilter_uses_render_style') )
                                   ->filter_get_articles_listing_by_article_type();

$where = array();
  			if ( $this->view->datafilter->isActive() ) {
  			  echo "<pre>"; echo date( 'i:s' ); echo "</pre>"; 
  			  
				require_once('Xplora/Datafilter/Sql.php');
				foreach ($this->view->datafilter->getFields() as $id=>$field) {
					if (true===$field->getActive() && strtolower($field->gettype())!='attribute') {
						$where[]=Xplora_Datafilter_Sql::getFieldCondition($field);
					}
				}
			}
echo "<pre>"; print_r( $where ); echo "</pre>";

    $this->view->articles   = $this->_module->getModel('Article')
                                            ->setPaginator_page($paginator_page)
                                            ->setDatasorter( $datasorter )
                                            ->setDatafilter( $this->view->datafilter )
                                            ->get_articles_listing_by_article_type( $this->_module->getConfig('core','article_type_announcement_id') );

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
    $article_seo = $this->getRequest()->getParam('seo');
    $this->view->article = $this->_module->getModel('Article')->read_article_by_seo_id( $article_seo );
    if( empty($this->view->article) ){
      $this->_module->exception(404);
    }

    $this->view->pageBreadcrumbs = $this->get_breadcrumbs( $this->getRequest()->getParam('action'), $this->view->article['title']  );
  }


  protected function get_breadcrumbs( $action = null, $title=null ){
    $base_breadcrumb = App::xlat('BREADCRUM_announcement');
    switch ( $action ){
      case 'announcement':
              return array(
                array('title'=> $base_breadcrumb )
              );
              break;

      case 'read':
              return array(
                array('title'=> $base_breadcrumb , 'url' => App::base( App::xlat('route_announcement') ) ),
                array('title'=> $title )
              );
              break;
      default:
              return null;
              break;
    }

  }

}