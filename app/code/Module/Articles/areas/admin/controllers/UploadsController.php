<?php
require_once 'Module/Articles/Controller/Action/Admin.php';

class Articles_UploadsController extends Module_Articles_Controller_Action_Admin {

	 function preDispatch(){
	   $this->designManager()->setCurrentLayout('ajax');
	 }

  function zipGalleryAction(){
    $this->_module->getModel('Uploads')->zip_gallery();
  }

  function imagesToGalleryAction(){
    $this->_module->getModel('Uploads')->image_to_gallery();
  }

  function uploadMainPixAction(){
    $this->_module->getModel('Uploads')->main_pix();
  }

  function uploadAudioAction(){
    $this->_module->getModel('Uploads')->audio();
  }

  function uploadDocsAction(){
    $this->_module->getModel('Uploads')->doc();
  }

}