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

  function get_book($book_seo_name = "" ){
    $select_details = $this->_db->select()
                      ->from(array('bo'  => 'rv60_books'  ), array('book_id','book','seo','lang_id') )
                      ->join(array('la'  => 'languages'   ), 'la.id = bo.lang_id', array('name','prefix','namespace','status') )
                      ->where('la.namespace = ?', App::locale()->getName() )
                      ->where('bo.seo = ?' , $book_seo_name)
                      ->limit(1);
    $book_details = $this->_db->query( $select_details )->fetch();

    if( empty($book_details) ){
      return null;
    }
    $book = null;

    $select_sumary = $this->_db->select()
                      ->from( array('la'  => 'languages')  , array() )
                      ->join( array('bi'  => 'rv60_bible') , 
                              'bi.lang_id = la.id',
                              array('ver_total' => 'COUNT(bi.ver)', 'cap_total' => 'COUNT( DISTINCT(bi.cap) )') )
                      ->where('bi.book_id = ?' , $book_details['book_id'])
                      ->where('la.id= ?', $book_details['lang_id'] );
    $book_sumary = $this->_db->query( $select_sumary )->fetch();
    $book['details'] = array_merge( $book_details, $book_sumary );


    $select_vercicles = $this->_db->select()
                      ->from(array('bi'  => 'rv60_bible'  ), array('book_id' ,'cap' ,'ver' ,'texto') )
                      ->join(array('la'  => 'languages'   ), 'la.id = bi.lang_id', array())
                      ->where('la.namespace = ?', App::locale()->getName() )
                      ->where('bi.book_id = ?' , $book_details['book_id'])
                      ->where('bi.cap = 1');
    $book['vercicles'] = $this->_db->query( $select_vercicles )->fetchAll();

    return $book;
  }

  function get_cap($book_seo_name = "", $cap_id = 0){

    $select = $this->_db->select()
                   ->from(array('bi'  => 'rv60_bible'  ), array('book_id' ,'cap' ,'ver' ,'texto') )
                   ->join(array('bo'  => 'rv60_books'  ), 'bo.book_id = bi.book_id AND bo.lang_id = bi.lang_id', array('book','seo') )
                   ->join(array('la'  => 'languages'   ), 'la.id = bi.lang_id', array('name', 'prefix', 'namespace'))
                   ->where('la.namespace = ?', App::locale()->getName() )
                   ->where('bo.seo = ?' , $book_seo_name)
                   ->where('bi.cap = ?' , $cap_id);
    $cap = $this->_db->query( $select )->fetchAll();

    return empty( $cap ) ? false : $cap; 
  }

  function get_ver($book_seo_name = "", $cap_id = 0, $ver_id = 0){

    $select = $this->_db->select()
                   ->from(array('bi'  => 'rv60_bible'  ), array('book_id' ,'cap' ,'ver' ,'texto','lang_id') )
                   ->join(array('bo'  => 'rv60_books'  ), 'bo.book_id = bi.book_id AND bo.lang_id = bi.lang_id', array('book','seo') )
                   ->join(array('la'  => 'languages'   ), 'la.id = bi.lang_id', array())
                   ->where('la.namespace = ?', App::locale()->getName() )
                   ->where('bo.seo = ?' , $book_seo_name)
                   ->where('bi.cap = ?' , $cap_id)
                   ->where('bi.ver = ?' , $ver_id);
    $ver = $this->_db->query( $select )->fetch();

    return empty( $ver) ? false : $ver; 
  }

}