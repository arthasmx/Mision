<?php
class Module_Core_Repository_Model_Libraries extends Core_Model_Repository_Model {

  function ajax_libs_required(){
    App::header()->addLink(App::skin('/css/admin.css'),array( "rel" => "stylesheet", "type" => "text/css" ) );
    App::header()->addLink(App::skin('/css/reset.css'),array( "rel" => "stylesheet", "type" => "text/css" ) );

    App::header()->addScript( App::url()->get('/jquery-1.8.0.min.js','js') );
  }

  function rhino_slider($id='#slider',$options=null){
    App::header()->addLink( App::www("/js/rhinoslider/css/rhinoslider-1.05.css"),array('rel'=>'stylesheet','type'=>'text/css'));
    App::header()->addScript(App::url()->get('/rhinoslider/js/rhinoslider-1.05.min.js','js'));

    if( !empty($options) && is_array($options) ){
      $parsed_options = array();
      foreach($options AS $key=>$value){
        $parsed_options[] = "$key : '$value'";
      }
    }else{
      $parsed_options = array( "effect      : 'slide'",
                               "effectTime  : 200",
                               "showTime    : 3000",
                               "autoPlay    : true",
                               "showBullets : 'always'",
                               "controlsMousewheel : false",
                               "controlsKeyboard : false");
    }

    App::header()->add_jquery_events("jQuery('$id').rhinoslider({". implode(",", $parsed_options)."});");
  }

  function addons_dropdown_menu(){
    App::header()->addLink( App::www("/js/addons-list/addons.css"),array('rel'=>'stylesheet','type'=>'text/css'));
  }

  function jquery_tools(){
    App::header()->addScript( App::url()->get('/jquery.tools.min.js','js') );
  }

  function jquery_tools_no_image_tabs($div_class=null){
    $this->jquery_tools();
    App::header()->addLink(App::skin('/css/tabs-no-images.css'),array( "rel" => "stylesheet", "type" => "text/css" ) );

    $ul = 'tabs'; $div = '';
    if( ! empty($div_class) ){
      $ul  = $div_class;
      $div = '.' . $div_class;
    }

    App::header()->add_jquery_events("jQuery('ul.$ul').tabs('div.panes > div$div');");

  }

  function json2(){
    App::header()->addScript(App::url()->get('/json2.js','js'));
  }

  function bible(){
    App::header()->addScript(App::url()->get('/bible.js','js'));
  }

  function bible_search(){
    App::header()->addCode("
          var bad_request  = '". App::xlat('EXC_bad_request') ."';
          var ajax_loading = '". App::xlat('AJAX_loading') ."';
          var empty_search = '". App::xlat('AJAX_empty_search') ."';
          var route_bible  = '". App::xlat('route_bible') ."';
          var load_books_url   = '". App::base() . App::xlat('route_bible') ."load-books';
          var choose_an_option = '". App::xlat('AJAX_choose_an_option') ."';
        ");
    $this->bible();
  }

  function bible_on_resize(){
    App::header()->add_jquery_events("set_element_position('div.opened','div.option',1);");
  }

  function delayed_error($msg=null, $target=null, $seconds=500){
    if( empty($msg) || empty($target) ){
      return null;
    }

    App::header()->add_jquery_events("show_error('".$msg."','".$target."','".$seconds."');");
  }

  function poll(){
    App::header()->addCode("var poll_on_error  = '". App::xlat('POLL_on_error') ."';");
    App::header()->addScript( App::url()->get('/poll.js','js') );
  }

  function colorbox(){
    App::header()->addLink(App::skin('/css/colorbox.css'),array('rel'=>'stylesheet'));
    App::header()->addScript(App::url()->get('/jquery.colorbox-min-'. App::locale()->getLang() .'.js','js'));
  }

  function cBox_google_maps(){
    App::header()->add_jquery_events("$('.fMap').colorbox({width:'640',height:'480',iframe:true});");
  }

  function google_map_launcher($id=null, $launcher=null, $coordinates=null){
    $addon_core = App::module('Addons')->getConfig('core','map');
    if( empty($id) || empty($coordinates) || empty($launcher) ){
      return null;
    }

    App::header()->addScript( "http://maps.googleapis.com/maps/api/js?key=".$addon_core['key']."&sensor=false" );

    App::header()->addCode("
      var myCenter = new google.maps.LatLng({$coordinates});
      var marker;

      function initialize() {
        var options = {
          center    : myCenter,
          zoom      : 15,
          mapTypeId : 'roadmap',
          scrollwheel        : false,
          draggableCursor    : 'default',
          streetViewControl  : false,
          disableDoubleClickZoom: true,
          zoomControlOptions : { style: google.maps.ZoomControlStyle.SMALL }
        };

        map = new google.maps.Map(document.getElementById('$id'), options);

        var marker = new google.maps.Marker({
          position : myCenter,
          map      : map
        });
      }
    ");

    App::header()->add_jquery_events("
      jQuery('$launcher').hover(function(){
        initialize();
      });
    ");
  }

  function google_map_to_pick_coordinates($id='googleMaps', $data=null){
    $addon_core = App::module('Addons')->getConfig('core','map');
    if( ! empty($data['reference']) ){
      $addon_core['city_coors'] = $data['reference'];
    }

    App::header()->addScript( "http://maps.googleapis.com/maps/api/js?key=".$addon_core['key']."&sensor=false" );

    App::header()->addCode("
      var myCenter = new google.maps.LatLng({$addon_core['city_coors']});
      var marker;

      function initialize() {
        var options = {
          center    : myCenter,
          zoom      : 15,
          mapTypeId : 'roadmap',
          draggableCursor    : 'default', 
          streetViewControl  : false,
          zoomControlOptions : { style: google.maps.ZoomControlStyle.SMALL }
        };
        map = new google.maps.Map(document.getElementById('$id'), options);

        var marker = new google.maps.Marker({
          position : myCenter,
          map      : map,
          draggable: true
        });

        google.maps.event.addListener(marker, 'dragend', function(a) {
          jQuery(articles.dom.current_cors).html( marker.getPosition().toUrlValue() );
        });

        if( ! jQuery('#$id').is('[data-initialized]') ){
          jQuery('#$id').attr('data-initialized','yes')
        }
      }
    ");
  }

  function gallery(){
    App::header()->addLink(App::skin('/css/gallery.css'),array('rel'=>'stylesheet'));
    App::header()->add_jquery_events("$('a.cBox').colorbox({rel:'cBox'});");
  }

  function youtube_video_player(){
    App::header()->add_jquery_events("$('a.cBox_youtube').colorbox({iframe:true, innerWidth:640, innerHeight:480});");
  }

  function import_easing(){
    App::header()->addScript( App::url()->get('/jquery.easing.1.3.js','js') );
  }

  function vertical_tabs($tab=null,$content=null){
    if( empty($tab) || empty($content) ){
      return null;
    }

    $this->jquery_tools();
    $this->import_easing();
    App::header()->addLink(App::skin('/css/tabs-vertical.css'),array('rel'=>'stylesheet','type'=>'text/css'));
    App::header()->add_jquery_events("
          jQuery('$tab').tabs('$content', {
            effect: 'fade',
            fadeOutSpeed: 0,
            fadeInSpeed: 500,
            onClick: function(event, index) {
              jQuery('$tab li').removeClass('vtab-selected');
              jQuery('a.current').parent().addClass('vtab-selected');
            }
          });
    ");
  }

  function tags($target=null){
    App::header()->addScript( App::url()->get('/tags/chosen.jquery.min.js','js') );
    App::header()->addLink( App::www("/js/tags/chosen.css"),array('rel'=>'stylesheet','type'=>'text/css'));
    App::header()->add_jquery_events("
      jQuery('.chzn-select').chosen({
        max_selected_options  : 10,
        no_results_text       : '".App::xlat('TAGS_no_results')."'
      });");
  }



  function jquery_ui($theme="humanity"){
    App::header()->addLink( App::www("/js/jquery-ui/$theme/custom.css"),array('rel'=>'stylesheet','type'=>'text/css'));
    App::header()->addScript(App::url()->get('/jquery-ui/jquery-ui-1.9.1.custom.min.js','js'));
  }

  function jquery_ui_tabs($id="tabs",$options=array()){
    $parsed_options = null;
    if( ! empty($options) && is_array($options) ){
      foreach ($options AS $key=>$value){
        $parsed_options .= "$key:$value,";
      }
    }

    App::header()->add_jquery_events("
      jQuery('#".$id."').tabs({
        ".trim($parsed_options,',')."
      });


    ");

  }

  function jquery_ui_icon_buttons($buttons=array(), $wrapper=null){
    if( ! empty($buttons) && is_array($buttons) ){

      $parsed_buttons = null;
      $parsed_options = null;
      foreach ($buttons AS $button){

        $parsed_options = null;
        if( ! empty($button['options']) ){
          foreach ($button['options'] AS $key=>$value){
            $parsed_options .= "$key:'$value',";
          }
        }

        $parsed_buttons .= "jQuery('$wrapper {$button['name']}').button({icons : { ".trim($parsed_options,",")."} });" . PHP_EOL;
      }

      App::header()->add_jquery_events($parsed_buttons);
    }

  }

  function jquery_ui_datepicker( $ids=array() ){
    if( App::locale()->getLang() == 'es' ){
      App::header()->addScript(App::url()->get('/jquery-ui/jquery.ui.datepicker-es.js','js'));
    }

    if( ! empty($ids) && is_array($ids) ){
      App::header()->addCode("var datepicker_options  = {changeMonth : true, changeYear : true, dateFormat : 'yy-mm-dd'};");

      $dates = null;
      foreach ($ids AS $id){
        $dates .= "jQuery( '".$id."' ).datepicker(datepicker_options);" . PHP_EOL;
      }

      App::header()->add_jquery_events($dates);
    }

  }

  function jquery_ui_dialog($target=null,$json_options=null){
    App::header()->add_jquery_events("jQuery('div#$target').dialog($json_options);");
  }



  function block_ui(){
    App::header()->addScript(App::url()->get('/jquery.blockUI.js','js'));
  }

  function articles(){
    App::header()->addScript(App::url()->get('/admin/articles.js','js'));
  }

  function categories(){
    App::header()->addScript(App::url()->get('/admin/categories.js','js'));
  }

  

  function articles_promotion(){
    App::header()->addLink(App::skin('/css/tabs-slideshow.css'),array( "rel" => "stylesheet", "type" => "text/css" ));

    App::header()->addScript( App::url()->get('/jquery.tools.min.js','js') );
    App::header()->add_jquery_events("
        jQuery('.slidetabs').tabs('.images > div', {
          effect: 'fade',
          fadeOutSpeed: 'slow',
          rotate: true
        }).slideshow({clickable:false, autoplay:true});
    ");
  }

  function jquery_tools_articles_tabs($tab_wraper=null){
    $this->jquery_tools();
    App::header()->addLink(App::skin('/css/tabs-no-images.css'),array( "rel" => "stylesheet", "type" => "text/css" ) );

    App::header()->add_jquery_events("
      jQuery('".$tab_wraper."').tabs('div.panes > div.pane', function(event, index) {
          var id = jQuery('input#article_id').attr('data-id');
          if (index == 1 && !id)  {
              return false;
          }
      });
    ");

  }

  function jquery_preview($previews=array()){
    $preview_code = '';
    foreach($previews AS $target=>$field){
      $preview_code .= PHP_EOL . "jQuery('$field').keyup(function(){ jQuery('.$target').html( jQuery(this).val() ); });";
    }

    App::header()->add_jquery_events($preview_code);

  }

  function ckeditor($id,$options=null){
    App::header()->addScript(App::url()->get('/ckeditor/ckeditor.js','js'));

    if( is_array($options) ){
      $parsed_options = array();
      foreach($options AS $key=>$value){
        $parsed_options[] = "$key : '$value'";
      }
    }

    App::header()->add_jquery_events("CKEDITOR.replace( '$id', {". implode(",", $parsed_options)."});");
  }

  function ckeditor_preview($id,$target){
    App::header()->add_jquery_events("
      CKEDITOR.instances['".$id."'].on('contentDom', function() {
        CKEDITOR.instances['".$id."'].document.on('keyup', function(event) {
          jQuery('".$target."').html( CKEDITOR.instances['".$id."'].getData() );
        });
      });
    ");
  }

  function clone_elements($id=null, $data=null){
    App::header()->addScript( App::url()->get('/jquery.sheepItPlugin-1.1.1.min.js','js') );

    $previous_data = null;
    if( ! empty($data) || is_array($data) ){
      $previous_data = ',data:' . json_encode($data);
    }

    App::header()->addCode(" var sheepItForm  = null; ");

    App::header()->add_jquery_events("
      sheepItForm = jQuery('$id').sheepIt({
        separator: '',
        allowRemoveLast: true,
        allowRemoveCurrent: true,
        allowRemoveAll: true,
        allowAdd: true,
        maxFormsCount: 10,
        minFormsCount: 0,
        iniFormsCount: 0,
        removeAllConfirmationMsg: '". App::xlat('sheep_it_remove_all_cloned_element_confirmation') ."'
        $previous_data
      });
    ");

/*
 * @todo: multiple cloner
    App::header()->add_jquery_events("
      jQuery('".implode(",",$ids)."').sheepIt({
        separator: '',
        allowRemoveLast: true,
        allowRemoveCurrent: true,
        allowRemoveAll: true,
        allowAdd: true,
        maxFormsCount: 10,
        minFormsCount: 0,
        iniFormsCount: 0,
        removeAllConfirmationMsg: '". App::xlat('sheep_it_remove_all_cloned_element_confirmation') ."'
        $previous_data
      });
    ");
    */
  }

  function rating($id=null){
    if( ! empty($id) ){
      App::header()->addScript( App::url()->get('/rating/jquery.raty.min.js','js') );
      App::header()->addLink( App::www('/js/rating/stylesheet.css'),array('rel'=>'stylesheet','type'=>'text/css'));

      App::header()->add_jquery_events("
          $('#rate').raty({
            target     : '#rate-hint',
            path       : '/js/rating/',
            scoreName  : 'rating',
            hints      : ['".App::xlat('RATING_1')."', '".App::xlat('RATING_2')."', '".App::xlat('RATING_3')."', '".App::xlat('RATING_4')."', '".App::xlat('RATING_5')."'],
            click      : function(score, evt) {

              $.ajax({
              url:  baseUrl + 'rate/".$id."/' + score,
              type: 'GET',
              success: function(response){
                jQuery('#rate').raty('readOnly', true);

                var json = jQuery.parseJSON(response);
                if(json.result == 'success'){
                  jQuery('#rate-answer').addClass('success').html('".App::xlat('RATING_on_success')."');
                }else{
                  jQuery('#rate-answer').addClass('error').html('".App::xlat('RATING_on_error')."');
                }

              },
              error: function(request, status, error){
                jQuery('#rate-answer').addClass('error').html('".App::xlat('RATING_on_error')."');
              }
              });

            }
          });
      ");
     }

  }
/*
  function files_paginator($colorbox_container=null,$colorbox_rel=null){
    App::header()->addScript( App::url()->get('/files-paginator.js','js') );

    App::header()->add_jquery_events("
      jQuery(document).on('click', 'div.f-pagination a', function(){
        if( jQuery(this).parent().hasClass('current') ){
          return false;
        }
        fp.paginate(jQuery(this).attr('data-page'),'$colorbox_container','$colorbox_rel');
      });
    ");
  }
*/

  function files_paginator(){
    App::header()->addScript( App::url()->get('/files-paginator.js','js') );
  }

  function files_paginate_gallery($target=null, $url=null, $cBox_container=null, $Box_rel=null){
    App::header()->add_jquery_events("
      jQuery(document).on('click', '$target', function(){
        if( jQuery(this).parent().hasClass('current') ){ return false; }
          fp.gallery('$url',jQuery(this).attr('data-page'),'$cBox_container','$Box_rel');
        });
    ");
  }

  function plUpload(){
    App::header()->addLink(App::www('/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css'),array('rel'=>'stylesheet','type'=>'text/css'));
    App::header()->addScript(App::url()->get('/plupload/plupload.full.js','js'));

    if( App::locale()->getLang()=='es' ){
      App::header()->addScript(App::url()->get('/plupload/i18n/es.js','js'));
    }
    App::header()->addScript(App::url()->get('/plupload/jquery.ui.plupload/jquery.ui.plupload.js','js'));
    App::header()->addScript(App::url()->get('/plupload/browserplus-min.js','js'));
  }

  function plUploadQueue(){
    App::header()->addLink(App::www('/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css'),array('rel'=>'stylesheet','type'=>'text/css'));
    App::header()->addScript(App::url()->get('/plupload/plupload.full.js','js'));
  
    if( App::locale()->getLang()=='es' ){
      App::header()->addScript(App::url()->get('/plupload/i18n/es.js','js'));
    }
    App::header()->addScript(App::url()->get('/plupload/jquery.plupload.queue/jquery.plupload.queue.js','js'));
    App::header()->addScript(App::url()->get('/plupload/browserplus-min.js','js'));
  }

  function plUpload_article_upload_files(){
    App::header()->addScript(App::url()->get("/admin/articles-upload-files.js",'js'));
  }

  function placeholder(){
    App::header()->addScript(App::url()->get('/jquery.placeholder.min.js','js'));
    App::header()->add_jquery_events("jQuery('input, textarea').placeholder();");
  }

  function jquery_treeview($id=null,$options=null){
    App::header()->addLink( App::www("/js/treeview/jquery.treeview.css"),array('rel'=>'stylesheet','type'=>'text/css'));
    App::header()->addScript(App::url()->get('/treeview/jquery.treeview.js','js'));

    if( is_array($options) ){
      $parsed_options = array();
      foreach($options AS $key=>$value){
        $parsed_options[] = "$key : '$value'";
      }
      $options = "{". implode(",", $parsed_options)."}";
    }else{
      $options=null;
    }

    App::header()->add_jquery_events("$('ul.$id').treeview($options);");
  }

}