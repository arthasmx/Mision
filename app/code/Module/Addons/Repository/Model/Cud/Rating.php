<?php
require_once 'Module/Core/Repository/Model/Db/Actions.php';
class Module_Addons_Repository_Model_Cud_Rating extends Module_Core_Repository_Model_Db_Actions{

  function rate_article($id = 0, $rate = 0){
    $this->is_multiple_database_modification_attempt('rate_votes');

    $valid_rate = $this->get_valid_rate($rate);

    $this->set_table('rate');
    if( $record_found = $this->already_in_database($id,'reference') ){
      $data  = array('votes'   => $record_found['votes']+1
                    ,'points' => $record_found['points']+$valid_rate );

      $where = $this->table->getAdapter()->quoteInto('reference = ?', $id);
      $this->table->update($data, $where);

    }else{
      $data = array('reference'=> $id  ,'votes'=> '1'  ,'points'=> $valid_rate );
      $this->table->insert($data);
    }

    $this->set_table('rate_votes');
    $data = array('rate_reference' => $id  
                  ,'ip'            => $_SERVER['REMOTE_ADDR']
                  ,'rate'          => $valid_rate
                  ,'created'       => date("Y-m-d H:i:s") );
    $this->table->insert($data);
  }

  function get_valid_rate($rate = 1){
    $allowed_rates = App::module('Addons')->getConfig('core','rating');
    if( array_key_exists($rate, $allowed_rates) ){
      return $rate;
    }
    return 1;
  }

}