<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Addons_Repository_Model_Youtube extends Module_Core_Repository_Model_Abstract {

  private $config  = false;
  private $youtube = false;

  function init(){
    $this->config = App::module("Addons")->getConfig("core","youtube");
    require_once 'Zend/Gdata/YouTube.php';
    $this->youtube = new Zend_Gdata_YouTube();
  }

  function get_video($id = null, $render_style = "block"){
    if( empty($id) ){
      return null;
    }

    try{
      $video = $this->youtube->getVideoEntry( $id );
    }catch(Exception $e){
      return null;
    }

    return $this->video_data($video, $render_style);
  }

  function get_user_videos($user=null){
    if( empty($user) ){
      return null;
    }

    $videos     = array();
    $video_list = $this->youtube->getUserUploads($user);
    foreach($video_list AS $video){
      $videos[] = $this->video_data($video);
    }
    return $videos;
  }

  private function video_data($video=null, $render_style = "block"){
    return ( ! empty($video) && $video->isVideoEmbeddable() ) ? array(
       'id'      => $video->getVideoId()
      ,'title'   => $video->getVideoTitle()
      ,'url'     => $this->get_flash_url( $video->mediaGroup->content )
      ,'page'    => $video->getVideoWatchPageUrl()
      ,'width'   => $this->config[$render_style]['width']
      ,'height'  => $this->config[$render_style]['height']
      ,'autoplay' => $this->config['autoplay']
      ,'thumbs'   => $video->getVideoThumbnails() )
    :
      null;
  }

  private function get_flash_url($uri){
    foreach ($uri as $content) {
      if ($content->type === 'application/x-shockwave-flash') {
        return $content->url;
      }
    }
    return null;
  }

}