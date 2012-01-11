<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Articles_Repository_Model_Sorterfilter extends Module_Core_Repository_Model_Abstract {

  function announcement_sort_rules(){
    $this->init_datasorter();

    $this->datasorter->createField( "id", Xplora_Datasorter::SORT_DESC );
    $this->datasorter->createField( "autor" );
    $this->datasorter->createField( "created" , Xplora_Datasorter::SORT_DESC );

    $this->datasorter->setDefault( "id" )->setSort($this->sort_f,$this->sort_t);

    if ( empty($this->datasorter) ){
      App::module('Core')->exception( App::xlat('EXC_article_datasorter') . '<br />Launched at method sort_list_articles_method, file Repository/Model/Datasorter' );
    }

    return $this;
  } 

  function announcement_filter_rules(){
    $this->init_datafilter( App::xlat('route_announcement') );

    $this->datafilter->createField( "id" , Xplora_Datafilter::TYP_TEXT )
                     ->setFieldName( "va.article_id" )
                     ->setAttribute( "size" , 6 );

    $this->datafilter->createField( "seo" , Xplora_Datafilter::TYP_TEXT )
                     ->setFieldName( "va.seo" )
                     ->setAttribute( "id" , "seo" )
                     ->setAttribute( "size" , 10 );

    return $this;
  }



  function events_sort_rules(){
    $this->init_datasorter();

    $this->datasorter->createField( "id", Xplora_Datasorter::SORT_DESC );
    $this->datasorter->createField( "autor" );
    $this->datasorter->createField( "created" , Xplora_Datasorter::SORT_DESC );

    $this->datasorter->setDefault( "id" )->setSort($this->sort_f,$this->sort_t);

    if ( empty($this->datasorter) ){
      App::module('Core')->exception( App::xlat('EXC_article_datasorter') . '<br />Launched at method sort_list_articles_method, file Repository/Model/Datasorter' );
    }

    return $this;
  }

  function events_filter_rules(){
    $this->init_datafilter( App::xlat('route_events') );

    $this->datafilter->createField( "id" , Xplora_Datafilter::TYP_TEXT )
    ->setFieldName( "va.article_id" )
    ->setAttribute( "size" , 6 );

    $this->datafilter->createField( "seo" , Xplora_Datafilter::TYP_TEXT )
    ->setFieldName( "va.seo" )
    ->setAttribute( "id" , "seo" )
    ->setAttribute( "size" , 10 );

    return $this;
  }

}