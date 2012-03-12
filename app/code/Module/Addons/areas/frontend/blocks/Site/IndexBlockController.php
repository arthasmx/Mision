<?php
require_once 'Core/Controller/Block.php';
class Addons_Site_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function getSiteEmailManagerAction(){}

  function mapAction(){
    App::header()->addCode("
        <script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?key=AIzaSyAIGddV3Qu8VuUNBJQ4oyjtU7SbR1On98Q&sensor=false'></script>
        <script type='text/javascript'>
        function initialize() {
          var myOptions = {
              zoom: 16,
              center: new google.maps.LatLng(23.249065, -106.429474),
              mapTypeId: google.maps.MapTypeId.ROADMAP
          };
          var map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
        }

        jQuery(document).ready(function(){
          initialize();
        });
        </script>");
  }

  function socialNetworksAction(){}

}