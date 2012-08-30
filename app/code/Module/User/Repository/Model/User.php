<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_User_Repository_Model_User extends Module_Core_Repository_Model_Abstract {

  public $user 			     = null;

  protected $basic_data  = null;

  function init(){
    $this->_namespace  = App::module('Core')->getModel('Namespace')->get( 'user' );
  }

  function login( $user=null, $pass=null){
    return $this->get_basic_data( $user, $pass );
  }

  function get_basic_data( $user=null, $pass=null ){
    if ( empty($user) || empty($pass) ){
      return false;
    }

    $select = $this->_db->select()
                   ->from(array('a' => 'acl'  ) )
                   ->join(array('u' => 'user' ), 'a.username = u.username')
                   ->where( 'a.username = ?', $user )
                   ->where( "a.passwd= SHA1(CONCAT('".$pass."', SHA1(a.created)))");

    $this->basic_data = $this->_db->query( $select )->fetch();

    if( ! empty($this->basic_data) ){
      $this->basic_data['session_life'] = App::module('Acl')->getModel('Acl')->refresh_session_time();
      $this->basic_data['menu']         = App::module('Addons')->getModel('Menu')->get($user);

      return $this->basic_data;
    }
    return false;
  }

  function save_user_data_to_session(){
    if ( empty($this->basic_data) ){
      return false;
    }

    $fields = array();
    $fields_to_store_in_session = array('username', 'name', 'last_name', 'maiden_name', 'avatar', 'folder', 'profession', 'mailing_list', 'lastlogin', 'session_life', 'menu');
    foreach ($this->basic_data as $key=>$value) {
      if ( in_array($key, $fields_to_store_in_session) ) {
       $fields[$key] = $value;
      }
    }
    $this->_namespace->user = $fields;
    return true;
  }

  function unload_user_data(){
   require_once('Zend/Session.php');
   Zend_Session::namespaceUnset('user');
   return true;
  }

  function get_privileges(){
    $select  = $this->_db->select()
                    ->from(array('vup' => 'vista_user_privileges' ) )
                    ->where( 'vup.username = ?', $this->user_data['username'] );

    $privileges = $this->_db->query( $select )->fetch();
    return empty($privileges) ? false : $privileges;
  }

}