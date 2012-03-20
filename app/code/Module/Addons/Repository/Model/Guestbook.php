<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Addons_Repository_Model_Guestbook extends Module_Core_Repository_Model_Abstract {

  function get_signs($current_page = 1, $enabled_only = true){
    $select = $this->_db->select()
                   ->from( array('g'  => 'guestbook') )
                   ->where('g.lang = ?', App::locale()->getLang() )
                   ->order('g.created DESC');

    if( $enabled_only === true ){
      $select->where('g.status = ?', "enabled");
    }

    $guestbook = $this->setPaginator_page($current_page)->paginate_query( $select );;
    return empty($guestbook)? null : $guestbook;
  }

}