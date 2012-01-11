<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Articles_Repository_Model_Datasorter extends Module_Core_Repository_Model_Abstract {

  function init(){
    $this->init_datasorter();
  }

  function sort_list_articles_method(){
    $this->datasorter->createField( "id", Xplora_Datasorter::SORT_DESC );
    $this->datasorter->createField( "autor" );
    $this->datasorter->createField( "created" , Xplora_Datasorter::SORT_DESC );

    $this->datasorter->setDefault( "id" )->setSort($this->sort_f,$this->sort_t);

    if ( empty($this->datasorter) ){
      App::module('Core')->exception( App::xlat('EXC_article_datasorter') . '<br />Launched at method sort_list_articles_method, file Repository/Model/Datasorter' );
    }
    return $this->datasorter;
  } 

}