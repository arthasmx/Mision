<?php
require_once 'Module/Articles/Controller/Action/Admin.php';

class Articles_FilesController extends Module_Articles_Controller_Action_Admin {

	 function preDispatch(){
	   $this->designManager()->setCurrentLayout('ajax');
	 }

  function mainPixPreviewAction(){
    $this->view->files = $this->_module->getModel('Files')->main_pix_preview();
  }




  function reloadGalleryAction(){
    $this->view->files = $this->_module->getModel('Files')->load_article_gallery();
  }

  function reloadAudioAction(){
    $this->view->files = $this->_module->getModel('Files')->load_article_audio();
  }

  function reloadFilesAction(){
    $this->view->files = $this->_module->getModel('Files')->load_article_files();
  }


  function deleteImageAction(){
    $this->_module->getModel('Files')->delete_image( $this->getRequest()->getParam('image') );
  }

  function deleteAudioAction(){
    $this->_module->getModel('Files')->delete_audio( $this->getRequest()->getParam('audio') );
  }

  function deleteFileAction(){
    $this->_module->getModel('Files')->delete_file( $this->getRequest()->getParam('file') );
  }




  function paginateGalleryAction(){
    $this->view->files = $this->_module->getModel('Files')->load_article_gallery( $this->getRequest()->getParam('page') );
  }

}