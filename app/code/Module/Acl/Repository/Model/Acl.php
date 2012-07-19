<?php
class Module_Acl_Repository_Model_Acl extends Core_Model_Repository_Model {

  public $life          = null;

  private $user_data    = null;
  private $user         = null;
  private $session_name = null;

  function init() {
    $this->user         = empty($this->user) ? App::module('User')->getModel('User')  :  $this->user;
    $this->session_name = App::module('User')->getConfig('core','session_name');
  }

  function login($user=null, $pwd=null) {
    $this->user_data = $this->user->login($user, $this->generate_salt($pwd) );
    if ( empty($this->user_data) ) {
      App::module('Core')->getModel('Flashmsg')->error(App::xlat('LOGIN_bad_credentials'));
      return false;
    }

    if( $this->is_user_enabled() ){
      $this->user->save_user_data_to_session();
      $this->update_user_access();

      header("Location: " . App::base('admin/') );
      exit;
    }
    return false;
  }

  private function is_user_enabled(){
    switch ($this->user_data['status']){
      case 'enabled':
          return true;
          break;
      case 'mustvalidate':
          $error_message = App::xlat('ERROR_MUSTVALIDATE');
          break;
      case 'banned':
          $error_message = App::xlat('ERROR_BANNED');
          break;
      case 'reported':
          $error_message = App::xlat('ERROR_REPORTED');
          break;
      case 'disabled':
      default:
          $error_message = App::xlat('ERROR_DISABLED');
          break;
    }
    App::module('Core')->getModel('Flashmsg')->error( str_replace('%email%', $this->user_data['username'], $error_message ) );
    $this->logout();
    return false;
  }

  private function update_user_access(){
    require_once 'Module/Core/Repository/Model/Db/Actions.php';
    $db = new Module_Core_Repository_Model_Db_Actions;

    $db->set_table('acl');
    $params = array('lastlogin' => date("Y-m-d H:i:s"), 'access_ip' => App::module('Core')->getModel('Parser')->get_ip() );
    $where  = $db->table->getAdapter()->quoteInto('username = ?', $this->user_data['username']);

    $db->table->update($params, $where);
  }

  private function generate_salt($password=null){
    if( empty($password) ){
      App::module('Core')->exception( 'Forbidden', 403 );
    }
    return md5($this->_module->getConfig('core','salt') . $password);
  }

  function logout() {
    $this->user->unload_user_data();
    header("Location: " . App::www('/login') );
    exit;
  }

  function is_user_logged() {
    $this->user_data = App::module('Core')->getModel('Namespace')->get( $this->session_name );

    if ( empty( $this->user_data->{$this->session_name} )  ||  ($this->user_data->{$this->session_name}['session_life'] <= time() )  ){
      App::module('Core')->getModel('Flashmsg')->error( App::xlat('ERROR_LOGIN_NOACTIVITY_NOPRIVILEGES') );
      $this->logout();
    }

    $this->user_data->{$this->session_name}['session_life'] = $this->refresh_session_time();
    return true;
  }

  function refresh_session_time(){
    return time() + $this->_module->getConfig('core','session_life');
  }

  function get_user_privileges(){
    if( empty($this->user_data->{$this->session_name}) ){
      App::module('Core')->getModel('Flashmsg')->error( App::xlat('ERROR_LOGIN_NOACTIVITY_NOPRIVILEGES') );
      $this->logout();
    }

    $core   = App::module('Core')->getModel('Abstract');
    $select = $core->_db->select()
                   ->from( array('p'  => 'privileges'),  array('p.id', 'p.privilege', 'p.picture') )
                   ->join( array('up' => 'user_privileges'), 'up.privilege = p.privilege',  array() )
                   ->where('up.username = ?', $this->user_data->{$this->session_name}['username']);

    $privileges = $core->_db->query( $select )->fetchAll();

    if( empty($privileges) ){
      App::module('Core')->getModel('Flashmsg')->error( App::xlat('ERROR_LOGIN_NOACTIVITY_NOPRIVILEGES') );
      $this->logout();
    }

    return $privileges;
  }

  function get_logged_user_data(){
    return empty($this->user_data->{$this->session_name}) ? null : $this->user_data->{$this->session_name};
  }

}