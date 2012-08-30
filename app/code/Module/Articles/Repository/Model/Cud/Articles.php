<?php
require_once 'Module/Core/Repository/Model/Db/Actions.php';
class Module_Articles_Repository_Model_Cud_Articles extends Module_Core_Repository_Model_Db_Actions{

  function stat($id=null, $status=null){
    if( empty($id) || empty($status) ){
      return false;
    }

    $this->set_table('articles_details');
    $data  = array('status' => $status);
    $where = $this->table->getAdapter()->quoteInto('article_id = ?', $id);
    $this->table->update($data, $where);
    return true;
  }

}