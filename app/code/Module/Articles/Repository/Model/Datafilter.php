<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Articles_Repository_Model_Datafilter extends Module_Core_Repository_Model_Abstract {

  function filter_get_articles_list_by_type(){
    $this->init_datafilter();

    $this->datafilter->createField( "id" , Xplora_Datafilter::TYP_TEXT )
                     ->setAttribute( "size" , 4 );

    return $this->datafilter_prepare();
  } 

}