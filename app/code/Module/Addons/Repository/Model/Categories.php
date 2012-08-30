<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Addons_Repository_Model_Categories extends Module_Core_Repository_Model_Abstract {

  function get_direct_childrens($parent_id=0, $username=null, $enabled_only=null){
    if( empty($parent_id) ){
      return null;
    }

    $select = $this->_db->select()
                   ->from( array('vc' => 'vista_categories') )
                   ->where('vc.parent = ?', $parent_id)
                   ->where('vc.language = ?', App::locale()->getLang() );

    if( ! empty($username) ){
      $select->where('vc.username = ?', $username);
    }
    if( ! empty($enabled_only) ){
      $select->where('vc.status = ?', 'enabled');
    }

    $childrens = $this->_db->query( $select )->fetchAll();
    return empty($childrens)? null : $childrens;
  }

  function get_direct_children_for_select($parent=null){
    $childrens       = $this->get_direct_childrens($parent);
    $children_select = array();

    foreach($childrens AS $children){
      $children_select[ $children['id'] ] = $children['name'];
    }

    return empty($children_select) ? null : $children_select;
  }

}