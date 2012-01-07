<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Articles_Repository_Model_Article extends Module_Core_Repository_Model_Abstract {

  protected $_totalLatest = 3;

  const MOREBREAK_TAG = '<!-- pagebreak -->';
  const MOREBREAK_SUBSTR = 500;

  function get_articles_list_by_type( $article_type = null, $paginate = true ){

    $articles = $this->_db->select()
                    ->from(array('va' => 'vista_articles' ) )
                    ->where( 'va.lang_status = 1' )
                    ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                    ->where( 'va.status = 1' )
                    ->where( 'va.publicated <= ?', date("Y-m-d h:i:s") )
                    ->where( 'va.written = 1' )
                    ->order( $this->add_datasorter() );

    if( ! empty( $article_type ) ){
      if( is_array($article_type) ){
        foreach($article_type AS $type){
          $articles->orWhere( 'va.article_type_id = ?', $type );
        }
      }else{
        $articles->where( 'va.article_type_id = ?', $article_type );
      }
    }

    if ( empty($paginate) ){
      return $this->_db->query( $articles )->fetchAll();
    }

    $articles = $this->setPaginator_query( $articles->__toString() )->paginate_query();

    return App::module('Core')->getModel('Abstract')
                              ->setPaginator_page_name(App::xlat('route_paginator_page'))
                              ->paginate_render( $articles );
  }

  function get_article_basic_data( $article_seo = 'not_given!' ){
    $article = $this->_db->select()
                    ->from(array('va' => 'vista_articles' ) )
                    ->where( 'va.lang_status = 1' )
                    ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                    ->where( 'va.seo = ?', $article_seo )
                    ->where( 'va.status = 1' )
                    ->where( 'va.written = 1' )
                    ->limit(1);
    $article = $this->_db->query( $article )->fetch();
    return empty( $article ) ? false : $article;
  }

  function get_article( $article_seo = "not_given!" ){
    $basic_data = $this->get_article_basic_data( $article_seo );

    if( empty($basic_data) ){
      App::module('Core')->exception( App::xlat('EXC_article_wasnt_found') . '<br />Launched at method get_article, file Repository/Model/Article' );
    }

    $select = $this->_db->select()
                   ->from(array('a'  => 'articles_details' ), array('article') )
                   ->where('a.seo = ?' , $article_seo);
    $article = $this->_db->query( $select )->fetch();
    return array_merge($basic_data , $article);
  }

}