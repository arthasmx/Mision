<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Addons_Repository_Model_Menu extends Module_Core_Repository_Model_Abstract {

  private $user_menus = array();

  function get($username= null, $menu_section_to_load = "admin-top"){
    if( empty($username) ){
      return null;
    }

    $select = $this->_db->select()
                   ->from(     array('vum' => 'vista_user_menus') )
                   ->join(     array('mp'  => 'menu_privileges') , 'mp.menu_id = vum.id', array() )
                   ->join(     array('p'   => 'privileges')      , 'p.name = mp.privilege', array() )
                   ->join(     array('up'  => 'user_privileges') , 'up.privilege = p.privilege', array() )
                   ->where('vum.section = ?', $menu_section_to_load)
                   ->where('up.username = ?', $username)
                   ->where('vum.status= ?', 'enabled')
                   ->group('vum.id')
                   ->order('vum.izq ASC');

    $this->user_menus = $this->_db->query( $select )->fetchAll();

    return empty($this->user_menus)? null : $this->parse_user_menu();
  }

  function parse_user_menu(){
    $current_parent = 0;
    $base_parent    = 0;
    $iteration      = 0;
    $user_menu      = array();

    foreach((array)$this->user_menus AS $menu){

      if( $current_parent != $menu['parent'] ){
        $current_parent = $menu['parent'];
      }
      if( $base_parent != $menu['parent'] ){
        $base_parent    = $menu['id'];
      }

      if( $current_parent == $base_parent ){ //sub-menu
        $user_menu[$iteration]['submenu'][$menu['sort']] = $menu;
      }else{ // root-menu
        $iteration++;
        $user_menu[$iteration] = $menu;
      }

    }

    return $user_menu;
  }

}