<?php
class Module_Articles_Repository_Model_Article extends Core_Model_Repository_Model{
  const MOREBREAK_TAG       = '<!-- pagebreak -->';
  const MOREBREAK_SUBSTR    = 500;

  private   $core           = null;
  protected $_totalLatest   = 3;

  function init(){
    $this->core = App::module('Core')->getModel('Abstract');
  }

  function get_article_list_by_type( $article_type=null, $current_page = null ){
    $select  = $this->core->_db->select()
                          ->from(array('va' => 'vista_articles' ) )
                          ->join(array('a'  => 'articles_details' ), 'a.article_id = va.article_id', array('a.description'))
                          ->where( 'va.lang_status = 1' )
                          ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                          ->where( 'va.status = 1' )
                          ->where( 'va.publicated <= ?', date("Y-m-d h:i:s") )
                          ->where( 'va.written = 1' )
                          ->where( 'va.article_type_id = ?', $article_type )
                          ->order( 'va.publicated DESC');

    return $this->core->setPaginator_page($current_page)->paginate_query( $select );
  }

  function get_articles_for_content_slider(){
    $articles  = $this->core->_db->select()
                      ->from(array('va' => 'vista_articles' ) )
                      ->join(array('a'  => 'articles_details' ), 'a.article_id = va.article_id', array('a.description'))
                      ->where( 'va.lang_status = 1' )
                      ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                      ->where( 'va.status = 1' )
                      ->where( 'va.publicated <= ?', date("Y-m-d h:i:s") )
                      ->where( 'va.written = 1' );

    $add_n_event_ids = array( $this->_module->getConfig('core','article_type_announcement_id'),
                              $this->_module->getConfig('core','article_type_event_id') );
    $articles->where( $this->core->grouped_where("va.article_type_id", $add_n_event_ids) );

    return $this->core->_db->query( $articles )->fetchAll();
  }

  function get_article_basic_data( $article_seo = 'not_given!' ){
    $article = $this->core->_db->select()
                    ->from(array('va' => 'vista_articles' ) )
                    ->where( 'va.lang_status = 1' )
                    ->where( 'va.lang_namespace = ?', App::locale()->getName() )
                    ->where( 'va.seo = ?', $article_seo )
                    ->where( 'va.status = 1' )
                    ->where( 'va.written = 1' )
                    ->limit(1);
    $article = $this->core->_db->query( $article )->fetch();
    return empty( $article ) ? false : $article;
  }

  function get_article( $article_seo = "not_given!" ){
    $basic_data = $this->get_article_basic_data( $article_seo );

    if( empty($basic_data) ){
      App::module('Core')->exception( App::xlat('EXC_article_wasnt_found') . '<br />Launched at method get_article, file Repository/Model/Article' );
    }

    $select = $this->core->_db->select()
                   ->from(array('a'  => 'articles_details' ), array('article') )
                   ->where('a.seo = ?' , $article_seo);
    $article = $this->core->_db->query( $select )->fetch();
    return array_merge($basic_data , $article);
  }

}