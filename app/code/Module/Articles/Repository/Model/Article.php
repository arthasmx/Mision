<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Articles_Repository_Model_Article extends Module_Core_Repository_Model_Abstract {

  protected $_totalLatest = 3;

  const MOREBREAK_TAG = '<!-- pagebreak -->';
  const MOREBREAK_SUBSTR = 500;

  function get_articles_listing_by_article_type( $article_type = null ){

    $articles = $this->_db->select()
                    ->from(array('va' => 'vista_articles' ) )
                    ->where( 'va.lang_status = 1' )
                    ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                    ->where( 'va.status = 1' )
                    ->where( 'va.publicated <= ?', date("Y-m-d h:i:s") )
                    ->where( 'va.written = 1' )
                    ->order( $this->add_datasorter() );

    if( ! empty( $article_type ) ){
      $articles->where( 'va.article_type_id = ?', $article_type );
    }

    $articles = $this->setPaginator_query( $articles->__toString() )->paginate_query();

    return App::module('Core')->getModel('Abstract')
                              ->setPaginator_page_name(App::xlat('route_paginator_page'))
                              ->paginate_render( $articles );
  }

  function read_article_by_seo_id( $article_seo = 'this_seo_was_intentionally_left_to_return_null_values_so_leave_it_as_is_ok?!' ){
    $article = $this->_db->select()
                    ->from(array('va' => 'vista_articles' ) )
                    ->where( 'va.lang_status = 1' )
                    ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                    ->where( 'va.seo = ?', $article_seo )
                    ->where( 'va.status = 1' )
                    ->where( 'va.written = 1' )
                    ->limit(1);
    return $this->_db->query( $article )->fetch();
  }

  function get_article_main_content( $article_id = null ){
    
  }

}