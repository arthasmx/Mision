<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Articles_Repository_Model_Article extends Module_Core_Repository_Model_Abstract{ // Core_Model_Repository_Model{

  protected $_totalLatest = 3;
  protected $current_model = null;

  const MOREBREAK_TAG = '<!-- pagebreak -->';
  const MOREBREAK_SUBSTR = 500;

  function init(){
    $this->current_model = App::module('Articles');
  }

  function get_announcement_list( $core_abstract = null ){
    $select  = $this->_db->select()
                    ->from(array('va' => 'vista_articles' ) )
                    ->where( 'va.lang_status = 1' )
                    ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                    ->where( 'va.status = 1' )
                    ->where( 'va.publicated <= ?', date("Y-m-d h:i:s") )
                    ->where( 'va.written = 1' )
                    ->where( 'va.article_type_id = ?', $this->current_model->getConfig('core','article_type_announcement_id') );

    return $core_abstract->setPaginator_page( @Core_Controller_Front::getInstance()->getRequest()->getParam( App::xlat('route_paginator_page') ) )
                         ->query_for_listing($select);
  }

  function get_events_list( $core_abstract = null ){
    $select  = $this->_db->select()
                         ->from(array('va' => 'vista_articles' ) )
                         ->where( 'va.lang_status = 1' )
                         ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                         ->where( 'va.status = 1' )
                         ->where( 'va.publicated <= ?', date("Y-m-d h:i:s") )
                         ->where( 'va.written = 1' )
                         ->where( 'va.article_type_id = ?', $this->current_model->getConfig('core','article_type_event_id') );

    return $core_abstract->setPaginator_page( @Core_Controller_Front::getInstance()->getRequest()->getParam( App::xlat('route_paginator_page') ) )
    ->query_for_listing($select);
  }

  function get_list($param = null, $type = null, $paginate = true, $core_abstract = null ){
    $articles  = $this->_db->select()
                      ->from(array('va' => 'vista_articles' ) )
                      ->where( 'va.lang_status = 1' )
                      ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                      ->where( 'va.status = 1' )
                      ->where( 'va.publicated <= ?', date("Y-m-d h:i:s") )
                      ->where( 'va.written = 1' );

    if( ! empty( $param ) ){
      switch($type){
        case 'seo':
                  $articles->where( 'va.article_type_seo = ?', $param);
              break;
        case 'id':
                if( is_array($param) ){
                  $grouped_where = App::module('Core')->getModel('Abstract')->grouped_where("va.article_type_id", $param);
                  $articles->where( $grouped_where );
                }else{
                  $articles->where( 'va.article_type_id = ?', $param );
                }
              break;
        default:
              App::module('Core')->exception( App::xlat('EXC_article_wrong_type') . '<br />Launched at method get_list, file Repository/Model/Article' );
          break;
      }
    }

    if ( empty($core_abstract) ){
      return $this->_db->query( $articles )->fetchAll();
    }

    return $core_abstract->query_for_listing($articles, $paginate);
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