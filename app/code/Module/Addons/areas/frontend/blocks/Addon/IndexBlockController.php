<?php
require_once 'Core/Controller/Block.php';
class Addons_Addon_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function googleMapAction(){
    App::module('Core')->getModel('Libraries')->google_map_launcher($this->getParam('id'), $this->getParam('launcher'), $this->getParam('coordinates') );
  }

  function miniGalleryAction(){
    $this->view->gallery = App::module('Articles')->getModel('Files')->get_gallery_thumbnails( $this->getParam('thumb') );
    $this->view->path    = $this->getParam('path');

    if( ! empty($this->view->gallery) && ! empty($this->view->path) ){
      App::header()->add_jquery_events("jQuery('a.cBox-mini-gallery').colorbox({rel:'cBox', width:'800',height:'533'}); ");
    }

  }

}