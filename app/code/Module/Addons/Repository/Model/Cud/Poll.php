<?php
require_once 'Module/Core/Repository/Model/Db/Actions.php';
class Module_Addons_Repository_Model_Cud_Poll extends Module_Core_Repository_Model_Db_Actions{

  function poll($id=0,$vote=null){
    /*
    if ( $this->is_multiple_database_modification_attempt('poll_votes','created',FALSE) ){
      return false;
    }
    */
    $this->set_table('poll_votes');
    $data = array('poll_id'   => $id  
                  ,'ip'       => $_SERVER['REMOTE_ADDR']
                  ,'vote'     => $vote
                  ,'created'  => date("Y-m-d H:i:s") );
    $this->table->insert($data);
    return true;
  }

}