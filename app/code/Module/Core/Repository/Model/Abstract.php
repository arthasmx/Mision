<?php
class Module_Core_Repository_Model_Abstract extends Core_Model_Repository_Model {

  public $id                  = null;
  public $strip               = true;
  public $paginator_page      = false;
  public $paginator_page_name = false;
  public $paginator_query     = false;

	protected $items_per_page  = 4;
	protected $datafilter      = false;
	protected $datafilter_render_style = false;
	protected $datasorter		   = false;
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
    if ( empty($this->datasorter) ){
      return null;
    }

    $order = false;
    if (is_array($sort=$this->datasorter->getSort())) {
      foreach ($sort as $field) {
        $order[] = $field->getFieldname()." ". $field->getSort_type();
      }
    }
    return $order;
  }

  public function datasorter_prepare(){
    if ( empty($this->datasorter) ){
      return false;
    }

    require_once "Local/View/Helper/Datasorter.php";
    $sorter = new Local_View_Helper_Datasorter();
    return $sorter->datasorter()->setDatasorter($this->datasorter);
  }

  // Datafilter

  public function init_datafilter($additional_route = 'you_forgot_to_set_the_route_for_method_init_datafilter'){
    require_once("Xplora/Datafilter.php");

    if ( ! empty($this->datafilter_render_style) ){
      $this->datafilter = Xplora_Datafilter::factory()->setUrl(
                            App::url()->removeParams( 
                              array(
                                App::xlat('route_paginator_page')  =>  Core_Controller_Front::getInstance()->getRequest()->getParam( App::xlat('route_paginator_page') )
                              ) ) );
    }else{
			$this->datafilter = Xplora_Datafilter::factory()->setUrl( App::base( $additional_route . $this->getRequest()->getParam('seo') ) );
    }

    $this->datafilter->setTranslator( App::translate()->getFormTranslator() )->setLocale( App::locale()->zend() );
  }

  public function datafilter_prepare(){
    if ( empty( $this->datafilter ) ){
      return false;
    }

    $this->datafilter->populate( Core_Controller_Front::getInstance()->getRequest()->getParams() );
    return $this->datafilter;
  }

}