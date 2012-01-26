<?php
require_once 'Core/Controller/Block.php';
class Addons_Gallery_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function loadAddonAction(){
    $addon  = $this->getParam('addon');
    $params = array( "id" => $this->getParam('id'), "created" => $this->getParam('created') );

    switch( $addon['addon_id'] ){
      case 1:
             break;

       case 2:
             break;

       case 3:
               $this->setScriptAction("gallery-addon");

               App::header()->addScript(App::url()->get('/highslide-with-gallery.packed.js','js'));
               App::header()->addCode("
                   <script type='text/javascript'>
                   hs.graphicsDir = '" . App::skin('/art/highslide/') . "';
                   </script>
               ");
               App::header()->addScript(App::url()->get('/highslide.config.js','js'));
               App::header()->addLink(App::skin('/css/gallery.css'),array('rel'=>'stylesheet','type'=>'text/css'));

               $this->view->gallery = $this->get_gallery( $params );
             break;
      default:
             break;
    }

  }

  private function get_gallery($article = null){
    return $this->_module->getModel('Gallery')->get_gallery_files( $article );
  }

}