<?php
class Module_Articles_Repository_Model_Article extends Core_Model_Repository_Model{
  const MOREBREAK_TAG       = '<!-- pagebreak -->';
  const MOREBREAK_SUBSTR    = 500;

  private   $core           = null;
  protected $_totalLatest   = 3;

  function init(){
    $this->core = App::module('Core')->getModel('Abstract');
  }

  function get_article_list( $current_page = null, $type=null, $publicated=false, $next_events=false, $written_only=false, $status="all"){
    $select  = $this->core->_db->select()
                          ->from(array('va' => 'vista_articles' ) )
                          ->join(array('a'  => 'articles_details' ), 'a.article_id = va.article_id', array('a.description'))
                          ->where( 'va.lang_status = ?', 'enabled' )
                          ->where( 'va.language = ?', App::locale()->getLang() )
                          ->order( 'va.publicated DESC');

    if( ! empty($type) ){
      $select->where( 'va.article_type_id = ?', $type );
    }
    if( $status!=="all" ){
      $select->where( 'va.status = ?', $status );
    }
    if( $publicated===true ){
      $select->where( 'va.publicated <= ?', date("Y-m-d h:i:s") );
    }
    if( $next_events===true ){
      $select->where( 'va.event_date >= ?', date("Y-m-d h:i:s") );
    }else{
      $select->where( "ISNULL(va.event_date) OR va.event_date < ?", date("Y-m-d h:i:s"));
    }
    if( $written_only===true ){
      $select->where( 'va.written = 1' );
    }

    return $this->core->setPaginator_page($current_page)->paginate_query( $select );
  }

  function get_articles_for_content_slider($category=null, $past_next=null, $limit=null){

    $articles  = $this->core->_db->select()
                      ->from(array('va' => 'vista_articles' ) )
                      ->join(array('a'  => 'articles_details' ), 'a.article_id = va.article_id', array('a.description'))
                      ->where( 'va.lang_status = ?', 'enabled' )
                      ->where( 'va.language = ?', App::locale()->getLang() )
                      ->where( 'va.status = "promote"' )
                      ->where( 'va.publicated <= ?', date("Y-m-d h:i:s") )
                      ->where( 'va.written = 1' )
                      ->order( 'va.article_id DESC' );

    if( $past_next==="next" ){
      $articles->where( 'va.event_date >= ?', date("Y-m-d h:i:s") );
    }
    if( ! empty($limit) ){
      $articles->limit( $limit );
    }

    if( ! empty($category) ){
      $articles->where( 'va.article_type_id = ?', $category );
    }else{
      $add_n_event_ids = array( $this->_module->getConfig('core','article_type_announcement_id'),
      $this->_module->getConfig('core','article_type_event_id') );

      $articles->where( $this->core->grouped_where("va.article_type_id", $add_n_event_ids) );
    }

    return $this->core->_db->query( $articles )->fetchAll();
  }

  function get_article_basic_data( $article_seo_OR_id = 'not_given!', $status="all" ){
    $article = $this->core->_db->select()
                    ->from(array('va' => 'vista_articles' ) )
                    ->where( 'va.lang_status = ?', 'enabled' )
                    ->where( 'va.language = ?', App::locale()->getLang() )
                    ->where( 'va.seo = ?', $article_seo_OR_id )
                    ->orWhere('va.article_id = ?' , $article_seo_OR_id)
                    ->where( 'va.written = 1' )
                    ->limit(1);

    if( $status!=="all" ){
      $article->where( $this->core->grouped_where("va.status", array('enabled','promote') ) );
    }

    $article = $this->core->_db->query( $article )->fetch();
    return empty( $article ) ? false : $article;
  }

  function get_article( $article_seo_OR_id = "not_given!" ){
    $basic_data = $this->get_article_basic_data( $article_seo_OR_id );

    if( empty($basic_data) ){
      App::module('Core')->exception( App::xlat('EXC_article_wasnt_found') . '<br />Launched at method get_article, file Repository/Model/Article' );
    }

    $select = $this->core->_db->select()
                   ->from(array('a'  => 'articles_details' ), array('article') )
                   ->where('a.seo = ?' , $article_seo_OR_id)
                   ->orWhere('a.article_id = ?' , $article_seo_OR_id);

    $article = $this->core->_db->query( $select )->fetch();
    return array_merge($basic_data , $article);
  }

  function get_article_addons($article_id = 0, $lang_id = 1){
    $select = $this->core->_db->select()
                         ->from(array('af'  => 'articles_files' ) )
                         ->where( 'af.status = "enabled"' )
                         ->where( 'af.article_id = ?', $article_id );

    $addons = $this->core->_db->query( $select )->fetchAll();
    return empty( $addons ) ? false : $addons;
  }

  function get_route_by_type($type_id=null){
    $route_by_type = array( $this->_module->getConfig('core','article_type_announcement_id')  => App::xlat('route_announcement')
                            ,$this->_module->getConfig('core','article_type_event_id')        => App::xlat('route_events')
                            ,$this->_module->getConfig('core','article_type_article_id')      => App::xlat('route_articles') );

    return empty($type_id) || !array_key_exists($type_id, $route_by_type) ?
             null
           :
             $route_by_type[$type_id];
  }
}