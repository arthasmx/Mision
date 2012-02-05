<?php
require_once 'Core/Controller/Block.php';
class Addons_Rating_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function getRatingAction(){
    $this->view->rating = $this->_module->getModel('Rating')
                                        ->get_rate( $this->getParam('id') );
  }

}