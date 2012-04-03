<?php
require_once 'Module/Core/Repository/Model/Abstract.php';
class Module_Addons_Repository_Model_Video extends Module_Core_Repository_Model_Abstract {

  private $config  = false;

  function init(){
    $this->config = App::module("Addons")->getConfig("core","video");
  }

  function time_to_show_live_sermon($go_live_now = null){
    if( ! empty( $go_live_now ) ){
      return true;
    }
    if( App::module('Addons')->getConfig('core','video_streaming')=="disabled"  ){
      return null;
    }

    $now    = strtotime( date('h:ia') );
    $day    = date('N'); // 1=monday, 7=sunday
    $dates  = App::module('Core')->getModel('Dates');

    foreach($this->config['hour'] AS $times){
      if( $day == $this->config['day'] && $dates->is_time_between_times($times['start'], $times['end'],$now) ){
        return true;
      }
    }
    return false;
  }

}