<?php
require_once 'Module/Addons/Controller/Action/Frontend.php';
class Addons_AjaxController extends Module_Addons_Controller_Action_Frontend   {

  function preDispatch(){
    $this->designManager()->setCurrentLayout('ajax');
  }

  function articleRateAction(){
    $this->_module->getModel('Cud/Rating')->rate_article( $this->getRequest()->getParam('id'), $this->getRequest()->getParam('rate') );
    $this->view->rating = $this->_module->getModel('Rating')->get_rate( $this->getRequest()->getParam('id') );
  }

  function pollVoteAction(){
    $id = $this->getRequest()->getParam('id');

    $poll = $this->_module->getModel('Cud/Poll')->poll( $id, $this->getRequest()->getParam('vote'));
    echo empty($poll)? 'false' : $this->_module->getModel('Poll')->get_results_chart($id);
    exit;
  }

}