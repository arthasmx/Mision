<?php
class Module_Core_Repository_Model_Abstract extends Core_Model_Repository_Model {

  public $id                  = null;
  public $strip               = true;
  public $paginator_page      = false;
  public $paginator_page_name = false;
  public $paginator_query     = false;

  protected $items_per_page  = 2;
  protected $datafilter      = false;
  protected $datasorter	     = false;
  protected $datafilter_render_style = false;

  protected $sort_f          = null;
  protected $sort_t          = null;
  protected $_namespace      = null;
  protected $_db             = null;
  protected $_query          = null;



  public function __construct($id) {
    $this->_db = App::module('Core')->getResource('Db')->get();
  }

  public function asArray() {
    $array=array();
    foreach ($this as $var=>$value) {
      if ($var[0]!="_" && $value!==false) $array[$var]=$value;
    }
    return $array;
  }

  public function reset() {
    foreach ($this as $var=>$value) {
      if ($var[0]!="_") { $this->{$var}=false; }
    }
    return $this;
  }

  public function __call($function, $args) {
    preg_match("/^set([a-zA-Z\_]+)$/",$function,$matches);
    if (isset($matches[1])) {
      $var=strtolower($matches[1]);
      if (isset($this->{$var}) || @$this->{$var}===false) {
          $this->{$var}=$args[0];
      }
      return $this;
    }

    preg_match("/^get([a-zA-Z\_]+)$/",$function,$matches);
    if (isset($matches[1])) {
        $var=strtolower($matches[1]);
        if (isset($this->{$var})) {
            return $this->{$var};
        }
        return false;
    }
  }

  public function query_for_listing($select=null){
    if( empty($select) || ! is_object($select) ){
      App::module('Core')->exception( App::xlat('EXC_db_instance_not_found') . '<br />Launched at method query, file Repository/Model/Abstract' );
    }

    if ( ! empty($this->datasorter) ){
      $select->order( $this->add_datasorter() );
    }

    if ( ! empty($this->datafilter) && $this->datafilter->isActive() ) {
      require_once('Xplora/Datafilter/Sql.php');
      foreach ($this->datafilter->getFields() as $id=>$field) {
        if ( true===$field->getActive() ) {
          $select->where( "{$field->getFieldName()} {$field->getCondition()} ?", $field->getValue() );
        }
      }
    }

    $select = $this->setPaginator_query( $select->__toString() )->paginate_query();
    return $this->setPaginator_page_name(App::xlat('route_paginator_page'))
                ->paginate_render( $select );
  }

  public function paginate_query(){
    if ( empty($this->paginator_query) ){
      return false;
    }

    require_once('Xplora/Paginate/Sql.php');
    $paginator=new Xplora_Paginate_Sql();
    return $paginator->setItems_per_page((int)$this->items_per_page)
                     ->setPage_current((int)$this->paginator_page)
                     ->setDb_adapter($this->_db)
                     ->setQuery($this->paginator_query)
                     ->paginate();
  }

  public function paginate_render($data = null){
    if ( empty($data) ){
      return null;
    }

    $pagination = null;
    if ( ! empty($data['pagination']['page_total']) &&  $data['pagination']['page_total']>1 ){
      require_once "Local/View/Helper/Paginate.php";
      $render = new Local_View_Helper_Paginate();
      $render->paginate()->setUrl(
            App::url()->removeParams(
              array(
                $this->paginator_page_name => Core_Controller_Front::getInstance()->getRequest()->getParam( $this->paginator_page_name )
              )
            )
          )
          ->setPage_current($data['pagination']['page_current'])
          ->setPage_total($data['pagination']['page_total'])
          ->setItems_per_page($data['pagination']['items_per_page'])
          ->setPaginator_page_name($this->paginator_page_name)
          ->setItems_total($data['pagination']['items_total']);

      $pagination = $render->renderPages();
    }

    return array_merge($data, array('pagination_html' => $pagination));
  }

  public function grouped_where($field = null, $grouped_field_values = array(), $and_or = "OR" ){
    if( ! is_array($grouped_field_values) || empty($field) ){
      return null;
    }

    $grouped_where = array();
    foreach($grouped_field_values AS $field_value){
      $grouped_where[] = $this->_db->quoteInto( $field . ' = ?', $field_value );
    }

    return empty($grouped_where) ? null : implode( " " . $and_or . " ", $grouped_where);
  }

  // Datasorter

  public function init_datasorter(){
    require_once("Xplora/Datasorter.php");
    $this->sort_f = (string)Core_Controller_Front::getInstance()->getRequest()->getParam( App::xlat('route_sort_field') );
    $this->sort_t = (string)Core_Controller_Front::getInstance()->getRequest()->getParam( App::xlat('route_sort_direction') );

    $this->datasorter = Xplora_Datasorter::factory()->setUrl(
                        App::url()->removeParams(
                          array(
                            App::xlat('route_sort_field')      =>  $this->sort_f,
                            App::xlat('route_sort_direction')  =>  $this->sort_t,
                            App::xlat('route_paginator_page')  =>  Core_Controller_Front::getInstance()->getRequest()->getParam( App::xlat('route_paginator_page') ),
                          )
                        )
                      );
  }

  public function add_datasorter(){
    $order = false;
    if (is_array($sort=$this->datasorter->getSort())) {
      foreach ($sort as $field) {
        $order[] = $field->getFieldname()." ". $field->getSort_type();
      }
    }
    return $order;
  }

  public function datasorter_to_render(){
    if ( empty($this->datasorter) ){
      return array();
    }

    require_once "Local/View/Helper/Datasorter.php";
    $sorter = new Local_View_Helper_Datasorter();
    return $sorter->datasorter()->setDatasorter($this->datasorter);
  }

  // Datafilter

  public function init_datafilter($additional_route = 'you_forgot_to_set_the_route_for_method_init_datafilter'){
    require_once("Xplora/Datafilter.php");

    if ( empty($this->datafilter_render_style) ){
      $this->datafilter = Xplora_Datafilter::factory()->setUrl( App::base( $additional_route ) );
    }else{
      $this->datafilter = Xplora_Datafilter::factory()->setUrl(
      App::url()->removeParams( array(
                                  App::xlat('route_paginator_page')  =>  Core_Controller_Front::getInstance()->getRequest()->getParam( App::xlat('route_paginator_page') )
                              )));
    }

    $this->datafilter->setTranslator( App::translate()->getFormTranslator() )->setLocale( App::locale()->zend() );
  }

  public function datafilter_to_render(){
    if ( empty( $this->datafilter ) ){
      return array();
    }
    $this->datafilter->populate( Core_Controller_Front::getInstance()->getRequest()->getParams() );
    return $this->datafilter;
  }

}