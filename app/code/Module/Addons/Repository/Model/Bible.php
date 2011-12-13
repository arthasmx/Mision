<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Addons_Repository_Model_Bible extends Module_Core_Repository_Model_Abstract {

  function get_phrase(){
    $session = App::module('Core')->getModel('Namespace')->get( 'array_random' );
    $select = $this->prepare_phrase_query();

    if( ! empty($session->array_random) ){
      foreach($session->array_random AS $phrase_already_shown){
        $select->Where(" NOT (ph.book_id = {$phrase_already_shown['book_id']} AND ph.cap_id = {$phrase_already_shown['cap_id']} AND ph.ver_id = {$phrase_already_shown['ver_id']} ) ");
      }
    }

    $result = $this->_db->query( $select )->fetch();

    if ( empty($result) ){
      $session->array_random = array();
      $select = $this->prepare_phrase_query();
      $result = $this->_db->query( $select )->fetch();
    }

    if( ! empty($result) ){
      $session->array_random[] = array(  'book_id' => $result['book_id']
                                         , 'cap_id' => $result['cap']
                                         , 'ver_id' => $result['ver']
                                       );
      return $result;
    }
    return null;
  }

  protected function prepare_phrase_query(){
    return $this->_db->select()
                     ->from(array('bi'  => 'rv60_bible'  ), array('book_id','cap','ver','lang_id','texto') )
                     ->join(array('ph'  => 'rv60_phrase' ), 'ph.book_id = bi.book_id AND ph.cap_id = bi.cap AND ph.ver_id = bi.ver', array())
                     ->join(array('bo'  => 'rv60_books'  ), 'bo.book_id = bi.book_id AND bo.lang_id = bi.lang_id', array('book','seo') )
                     ->join(array('la'  => 'languages'   ), 'la.id = bi.lang_id')
                     ->where('la.namespace = ?', App::locale()->getName() )
                     ->limit(1);
  }

  function get_book_chapters_and_verses_summary($book_id = 0, $lang_id = 0){
    $select = $this->_db->select()
                   ->from( array('la'  => 'languages')  , array() )
                   ->join( array('bi'  => 'rv60_bible') , 
                                 'bi.lang_id = la.id',
                           array('verses' => 'COUNT(bi.ver)', 'chapter' => 'COUNT( DISTINCT(bi.cap) )') )
                   ->where('bi.book_id = ?' , $book_id)
                   ->where('la.id= ?', $lang_id );
    $sumary = $this->_db->query( $select )->fetch();

    return empty( $sumary) ? false : $sumary;
  }

  function get_book_details($book_seo_name = "not_given"){
      $select = $this->_db->select()
                     ->from(array('bo'  => 'rv60_books'  ), array('book_id','book','seo','lang_id') )
                     ->join(array('la'  => 'languages'   ), 'la.id = bo.lang_id', array('name','prefix','namespace','status') )
                     ->where('la.namespace = ?', App::locale()->getName() )
                     ->where('bo.seo = ?' , $book_seo_name)
                     ->limit(1);
    $details = $this->_db->query( $select )->fetch();

    if( empty($details) ){
      return null;
    }

    $summary = $this->get_book_chapters_and_verses_summary($details['book_id'], $details['lang_id']);

    return array_merge($details, $summary);
  }

  function get_verses($book_seo_name = "not_given", $chapter_id = 0){
    $select = $this->_db->select()
                   ->from(array('bi'  => 'rv60_bible'  ), array('book_id' ,'cap' ,'ver' ,'texto') )
                   ->join(array('bo'  => 'rv60_books'  ), 'bo.book_id = bi.book_id AND bo.lang_id = bi.lang_id', array('book','seo') )
                   ->join(array('la'  => 'languages'   ), 'la.id = bi.lang_id', array('name', 'prefix', 'namespace'))
                   ->where('la.namespace = ?', App::locale()->getName() )
                   ->where('bo.seo = ?' , $book_seo_name)
                   ->where('bi.cap = ?' , $chapter_id);
    $verses = $this->_db->query( $select )->fetchAll();

    return empty( $verses ) ? false : $verses;
  }

  function get_verse($book_seo_name = "not_given", $cap_id = 0, $ver_id = 0){

    $select = $this->_db->select()
                   ->from(array('bi'  => 'rv60_bible'  ), array('book_id' ,'cap' ,'ver' ,'texto','lang_id') )
                   ->join(array('bo'  => 'rv60_books'  ), 'bo.book_id = bi.book_id AND bo.lang_id = bi.lang_id', array('book','seo') )
                   ->join(array('la'  => 'languages'   ), 'la.id = bi.lang_id', array())
                   ->where('la.namespace = ?', App::locale()->getName() )
                   ->where('bo.seo = ?' , $book_seo_name)
                   ->where('bi.cap = ?' , $cap_id)
                   ->where('bi.ver = ?' , $ver_id);
    $verse = $this->_db->query( $select )->fetch();

    return empty( $verse ) ? false : $verse; 
  }

  function get_books(){
    $select = $this->_db->select()
                   ->from(array('bo'  => 'rv60_books'  ), array('book_id' ,'book' ,'seo', 'testament') )
                   ->join(array('la'  => 'languages'   ), 'la.id = bo.lang_id', array())
                   ->where('la.namespace = ?', App::locale()->getName() )
                   ->group( array ('bo.book_id') )
                   ->order( array('bo.book_id ASC') );

    $res = $this->_db->query( $select )->fetchAll();
    return empty( $res ) ? false : $res;
  }

}