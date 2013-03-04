<?php
class Module_Articles_Repository_Model_Files extends Core_Model_Repository_Model{

  private   $core           = null;
  private   $session        = null;
  private   $image_config   = null;
  private   $folder_config  = null;

  function init(){
    $this->core          = App::module('Core')->getModel('Abstract');
    $this->session       = App::module('Core')->getModel('Namespace')->get( 'article' );
    $this->folder_config = $this->_module->getConfig('core','folders');
  }

  function main_pix_preview(){
    $required_images = array('slider', 'article', 'promote', 'listing', 'aside', 'mobile');
    App::module('Core')->getModel('Namespace')->clear('mainpix');

    $path            = $this->session->article['folders']['gallery'].DS;
    $session         = App::module('Core')->getModel('Namespace')->get( 'mainpix' );
    $session->mainpix['path'] = $this->session->article['folders']['url'];

    foreach( $required_images AS $image ){
      if( App::module('Core')->getModel('Filesystem')->check_folder( $path.$image.'.jpg' ) ){
        $session->mainpix['images'][$image]=$image.'.jpg'; 
      }else{
        $session->mainpix=null;
        break;
      }
    }

    return $session->mainpix;
  }

  function load_article_gallery($page=1,$max_files_to_show=28){
    $session_gallery = App::module('Core')->getModel('Namespace')->get( 'files' );
    unset( $session_gallery->files['admin_gallery'] );

    $files = App::module('Core')->getModel('Filesystem')->get_files_from_path( $this->session->article['folders']['thumbnails'], array( "include" => "/\.jpg$/i") );
    if ( empty( $files ) ){
      return null;
    }

    $session_gallery->files['admin_gallery']['path']  = $this->session->article['folders']['url'];
    $session_gallery->files['admin_gallery']['files'] = $files;

    // sets file name counter (edit required)
      if( empty( $this->session->article['file_name_counter'] ) ){
        $this->session->article[ 'file_name_counter'] = count($files) + 100;
      }

    if( count($files) > $max_files_to_show ){
      return App::module('Core')->getModel('Filesystem')->paginate_files_in_folder('admin_gallery',$page,$max_files_to_show);
    }

    return array('files' => $files
                ,'path'  => $this->session->article['folders']['url'] );
  }

  function get_images_files_and_paginate($page=1){
    return $this->load_article_gallery($page);
  }

  function load_article_audio($page=1,$max_files_to_show=30){
    $session_audio = App::module('Core')->getModel('Namespace')->get( 'files' );
    unset( $session_audio->files['admin_audio'] );

    $files = App::module('Core')->getModel('Filesystem')->get_files_from_path( $this->session->article['folders']['path'].$this->folder_config['audio'], array( "include" => "/\.mp3$/i") );
    if ( empty( $files ) ){
      return null;
    }

    $session_audio->files['admin_audio']['path']  = $this->session->article['folders']['url'].$this->folder_config['audio'];
    $session_audio->files['admin_audio']['files'] = $files;

    if( count($files) > $max_files_to_show ){
      return App::module('Core')->getModel('Filesystem')->paginate_files_in_folder('admin_audio',$page,$max_files_to_show);
    }

    return array('files' => $files
                ,'path'  => $this->session->article['folders']['url'].$this->folder_config['audio'] );
  }

  function load_article_files($page=1,$max_files_to_show=30){
    $session_files = App::module('Core')->getModel('Namespace')->get( 'files' );
    unset( $session_files->files['admin_files'] );

    $files = App::module('Core')->getModel('Filesystem')->get_files_from_path( $this->session->article['folders']['path'].$this->folder_config['files'], array( "include" => array("/\.pdf$/i","/\.doc$/i","/\.xls$/i","/\.txt$/i","/\.zip$/i","/\.rar$/i","/\.jpg$/i","/\.png$/i","/\.docx$/i","/\.xlsx$/i","/\.pptx$/i","/\.ppt$/i") ) );
    if ( empty( $files ) ){
      return null;
    }

    $session_files->files['admin_files']['path']  = $this->session->article['folders']['url'].$this->folder_config['files'];
    $session_files->files['admin_files']['files'] = $files;
  
    if( count($files) > $max_files_to_show ){
      return App::module('Core')->getModel('Filesystem')->paginate_files_in_folder('admin_files',$page,$max_files_to_show);
    }
  
    return array('files' => $files
        ,'path'  => $this->session->article['folders']['url'].$this->folder_config['files'] );
  }



  function get_gallery_thumbnails($thumb=null){
    if( empty($thumb) ){
      return null;
    }
    $files = App::module('Core')->getModel('Filesystem')->get_files_from_path( $thumb, array( "include" => "/\.jpg$/i") );
    return empty($files)?
      null
    :
      $files;
  }



  function delete_image($image=null){
    if( empty( $this->session->article['article_id'] ) || empty($image) ){
      die('{"status":false, "message":"'. App::xlat('jSon_error_image_deleted') .'"}');
    }

    $file  = $this->session->article['folders']['gallery'].DS.$image;
    $thumb = $this->session->article['folders']['thumbnails'].DS.$image;
    $fSys  = App::module('Core')->getModel('Filesystem');

    if( ! $fSys->check_folder( $file ) || $fSys->delete($thumb)===false ){
      die('{"status":false, "message":"'. App::xlat('jSon_error_image_deleted') .'"}');
    }
    $fSys->delete( $file );
    die('{"status":true, "message":"'. App::xlat('jSon_success_image_deleted') .'"}');
  }

  function delete_audio($audio=null){
    if( empty( $this->session->article['article_id'] ) || empty($audio) ){
      die('{"status":false, "message":"'. App::xlat('jSon_error_audio_deleted') .'"}');
    }

    $file  = $this->session->article['folders']['path']. $this->folder_config['audio'] .DS. $audio;
    $fSys  = App::module('Core')->getModel('Filesystem');

    if( ! $fSys->check_folder( $file ) || $fSys->delete( $file )===false ){
      die('{"status":false, "message":"'. App::xlat('jSon_error_audio_deleted') .'"}');
    }

    $this->_module->getModel('Cud/Articles')->delete_addon_relation($this->session->article['article_id'], $audio, 'audio');
    unset( $this->session->article['addons']['audio'][ array_search( $audio, $this->session->article['addons']['audio'] ) ]  );
    die('{"status":true, "message":"'. App::xlat('jSon_success_audio_deleted') .'"}');
  }

  function delete_file($file=null){
    if( empty( $this->session->article['article_id'] ) || empty($file) ){
      die('{"status":false, "message":"'. App::xlat('jSon_error_file_deleted') .'"}');
    }

    $full_file = $this->session->article['folders']['path']. $this->folder_config['files'] .DS. $file;
    $fSys      = App::module('Core')->getModel('Filesystem');

    if( ! $fSys->check_folder( $full_file ) || $fSys->delete( $full_file )===false ){
      die('{"status":false, "message":"'. App::xlat('jSon_error_file_deleted') .'"}');
    }

    $this->_module->getModel('Cud/Articles')->delete_addon_relation($this->session->article['article_id'], $file, 'files');
    unset( $this->session->article['addons']['files'][ array_search( $file, $this->session->article['addons']['files'] ) ]  );
    die('{"status":true, "message":"'. App::xlat('jSon_success_file_deleted') .'"}');
  }

}