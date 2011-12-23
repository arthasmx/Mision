<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Articles_Repository_Model_Datasorter extends Module_Core_Repository_Model_Abstract {

  function init(){
    $this->init_datasorter();
  }

  function sort_get_articles_list_by_type(){
    $this->datasorter->createField( "id", Xplora_Datasorter::SORT_DESC );
    $this->datasorter->createField( "autor" );
    $this->datasorter->createField( "created" , Xplora_Datasorter::SORT_DESC );

    $this->datasorter->setDefault( "id" )->setSort($this->sort_f,$this->sort_t);

    if ( empty($this->datasorter) ){
      return null;
    }
    return $this->datasorter;
  } 

}