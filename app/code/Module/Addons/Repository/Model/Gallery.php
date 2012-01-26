<?php
class Module_Addons_Repository_Model_Gallery extends Core_Model_Repository_Model{

  private $_gallery_basepath = null;
  private $_thumbnails_path  = null;
  private $_allowed_ext      = null;
  private $_article_path     = null; // Article creation date (year / month)


  function init() {
    $config = $this->_module->getConfig('core','gallery');

    $this->_gallery_basepath  = $config['basepath'];
    $this->_thumbnails_path   = $config['thumbnails'];
    $this->_allowed_ext       = $config['extension'];
  }

  function get_gallery_base_path(){
    return $this->_gallery_basepath;
  }

  function get_thumbnails_base_path(){
    if( empty($this->_article_path) ){
      return false;
    }
    return App::getConfig('media_folder'). $this->get_gallery_base_path().$this->_article_path.$this->_thumbnails_path;
  }

  function set_article_base_path($article = null){
    if( empty($article['created']) || empty($article['id'])){
      return null;
    }
    $this->_article_path = App::module('Core')->getModel('Dates')->toDate(8, $article['created']) . DS . $article['id'];
  }

  function get_gallery_files($article = null){
    if ( empty($article) ){
      return null;
    }
    $this->set_article_base_path($article);

    $thumbs            = $this->get_thumbnails_base_path();
    $filesys           = App::module('Core')->getModel('Filesystem');
    $allowed_regex_ext = $this->get_allowed_extensions_regex();

    $images = $filesys->get_files_from_path($thumbs,array(
	  "include"	=>	$allowed_regex_ext,
    ));

    if ( sizeof($images) < 1) {
      return false;
    } else {
      return array('thumbnails' => $images, 'path' => $this->_article_path );
    }

  }

  function get_allowed_extensions() {
    return $this->_allowed_ext;
  }

  function get_allowed_extensions_regex() {
    $regex = array();
    foreach ($this->get_allowed_extensions() as $ext=>$allowed) {
      $regex[$ext] = $allowed['regex'];
    }
    return $regex;
  }

}