<?php
class Module_Core_Repository_Model_Libraries extends Core_Model_Repository_Model {

  function highslide(){
    App::header()->addScript(App::url()->get('/highslide.js','js'));
  }

  function highslide_css(){
    App::header()->addLink(App::skin('/css/highslide.css'),array('rel'=>'stylesheet','type'=>'text/css'));
  }

  function highslide_html(){
    $this->highslide();
    $this->highslide_css();
    App::header()->addScript(App::url()->get('/highslide.with.html.config.js','js'));
    App::header()->addCode("
          hs.graphicsDir = '" . App::skin('/art/highslide/') . "';
          hs.outlineType = 'rounded-white';
          hs.outlineWhileAnimating = true;
    ");
  }

  function jquery_tools(){
    App::header()->addScript( App::url()->get('/jquery.tools.min.js','js') );
  }

  function jquery_tools_no_image_tabs(){
    $this->jquery_tools();
    App::header()->addLink(App::skin('/css/tabs-no-images.css'),array( "rel" => "stylesheet", "type" => "text/css" ) );

    App::header()->add_jquery_events("jQuery('ul.tabs').tabs('div.panes > div');");

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

  function content_slider_ddslider(){
    App::header()->addLink(App::skin('/css/DDSlider.css'),array('rel'=>'stylesheet','type'=>'text/css'));

    App::header()->addScript( App::url()->get('/jquery.easing.1.3.js','js') );
    App::header()->addScript( App::url()->get('/jquery.DDSlider.min.js','js') );

    App::header()->add_jquery_events("
            jQuery('#ddSlider').DDSlider({
              trans     : 'square',
              nextSlide : '.slider_arrow_right',
              prevSlide : '.slider_arrow_left',
              selector  : '.slider_selector'
            });");
  }

  function articles(){
    App::header()->addScript(App::url()->get('/admin/articles.js','js'));
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

  function jquery_tools_wizard_tabs(){
    $this->jquery_tools();
    App::header()->addLink(App::skin('/css/tabs-no-images.css'),array( "rel" => "stylesheet", "type" => "text/css" ) );
  }

  function jquery_date_picker($picker_elements){
    App::header()->addScript(App::url()->get('/datepicker/mobiscroll-2.0.2.custom.min.js','js'));
    App::header()->addLink(App::www('/js/datepicker/mobiscroll-2.0.2.custom.min.css'),array('rel'=>'stylesheet','type'=>'text/css'));

    $js_open  = PHP_EOL ."var now = new Date(); var options = {preset:'date', minDate: new Date(now.getFullYear(), now.getMonth(), now.getDate()), theme:'sense-ui', display:'inline', mode:'mixed', dateFormat:'yy-mm-dd', dateOrder:'D dd M yy', monthNamesShort:['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], dayNamesShort:['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'], monthText:'Mes', dayText:'Día', yearText:'Año', onShow:function(html,inst){ jQuery(this).val( inst.val ) }};";

    $date_pickers = '';
    foreach($picker_elements AS $picker){
      $date_pickers .= PHP_EOL. "jQuery('#$picker').scroller(options);";
    }

    App::header()->add_jquery_events($js_open . $date_pickers);
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

  function image_uploader( $id='image-upload'){
    App::header()->addScript(App::url()->get('/admin/uploader/uploader.js','js'));
    App::header()->addLink(App::www('/js/admin/uploader/uploader.css'),array('rel'=>'stylesheet','type'=>'text/css'));

    App::header()->add_jquery_events("
            var uploader = new qq.FileUploader({
              element: document.getElementById('$id')
              ,action: baseUrl + '/uploader/image-upload'
            }); ");
  }

  function append_form_controls($ids=null){
    App::header()->addScript(App::url()->get('/jquery.appendo.js','js'));

    if( is_array($ids) ){
      foreach($ids AS $id){
        App::header()->add_jquery_evenst("jQuery('$id').appendo({labelAdd: '". App::xlat('FORM_add_another_row') ."', labelDel: '". App::xlat('FORM_remove_row') ."'});");
      }
    }
  }

  function uploader( $ids=array('image-upload'), $url=array('/uploader/image-upload')){
    if( is_array($ids) && ( count($ids) == count($url) ) ){
      App::header()->addScript(App::url()->get('/uploader/uploader.js','js'));
      App::header()->addLink(App::www('/js/uploader/uploader.css'),array('rel'=>'stylesheet','type'=>'text/css'));
      App::header()->addCode(PHP_EOL);

      foreach($ids AS $key=>$id){
        App::header()->add_jquery_events("var $id = new qq.FileUploader({ element: document.getElementById('$id'), action: baseUrl + '$url[$key]'});");
      }
    }

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

}