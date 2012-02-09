<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once 'Zend/Db/Table.php';
class Module_Core_Repository_Model_Db_Actions extends Zend_Db_Table_Abstract {

  public $table = null;

  public function __construct() {
    $config  = Core_Model_Config::getConfigIni("db");
    $options = array ('host'     => $config->db->config->host,
                      'username' => $config->db->config->user,
                      'password' => $config->db->config->pass,
                      'dbname'   => $config->db->config->db
    );
    $db = Zend_Db::factory('PDO_MYSQL', $options);
    Zend_Db_Table_Abstract::setDefaultAdapter($db);
  }

  function set_table($name = null){
    if( empty($name) ){
      App::module('Core')->exception( App::xlat('EXC_table_name_missing') . '<br />Launched at method set_table, file Repository/Model/Db/Actions' );
    }
    $this->table = new Zend_Db_Table($name);
  }

  function already_in_database($id=0, $field=null){
    if( empty($field) ){
      App::module('Core')->exception( App::xlat('EXC_table_field_name_missing') . '<br />Launched at method already_in_database, file Repository/Model/Db/Actions' );
    }
    $select = $this->table->select()->where("$field = ?", $id);
    $result = $this->table->fetchRow( $select );
    return empty($result)? null : $result->toArray();  
  }

  function is_multiple_database_modification_attempt($table=null, $field='created', $throw_exception = true){
    $this->set_table($table);
    $allow_table_modifications_if_no_records_were_changed_within_this_time = App::module('Core')->getModel('Dates')->rest_hours_to_date();

    $select = $this->table->select()
                          ->from($this->table, array('already_modified' => 'COUNT(*)'))
                          ->where("$field BETWEEN '$allow_table_modifications_if_no_records_were_changed_within_this_time' AND '". date('Y-m-d H:i:s') . "'" );

    if ( empty($this->table->fetchRow($select)->already_modified ) ){
      return false;
    }

    if( $throw_exception ){
      App::module('Core')->exception( App::xlat('EXC_multiple_database_modifications') );
    }else{
      return true;
    }
  }

}