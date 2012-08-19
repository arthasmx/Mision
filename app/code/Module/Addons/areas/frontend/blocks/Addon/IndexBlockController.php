<?php
require_once 'Core/Controller/Block.php';
class Addons_Addon_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function loaderAction(){

    $addon  = $this->getParam('addon');
    $params = array( "id" => $this->getParam('id'), "created" => $this->getParam('created') );

    switch( $addon['type'] ){
      case 'video':
             $this->setScriptAction("loader-video");
             break;

       case 'audio':
             $this->setScriptAction("loader-audio");
             break;

       case 'gallery':
             $this->view->gallery = $this->get_gallery( $params );
             App::module('Core')->getModel('Libraries')->gallery();
             $this->setScriptAction("loader-gallery");
             break;

       case 'file':
             $this->setScriptAction("loader-file");
             break;

      default:
             break;
    }

  }

  private function get_gallery($article = null){
    return $this->_module->getModel('Gallery')->get_gallery_files( $article );
  }

}