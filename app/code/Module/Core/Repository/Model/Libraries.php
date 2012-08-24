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
        <script>
          hs.graphicsDir = '" . App::skin('/art/highslide/') . "';
          hs.outlineType = 'rounded-white';
          hs.outlineWhileAnimating = true;
        </script>
    ");
  }

  function jquery_tools(){
    App::header()->addScript( App::url()->get('/jquery.tools.min.js','js') );
  }

  function jquery_tools_no_image_tabs(){
    $this->jquery_tools();
    App::header()->addLink(App::skin('/css/tabs-no-images.css'),array(
        "rel"=>"stylesheet",
        "type"=>"text/css",
        "media"=>"all",
    ));

    App::header()->addCode("
        <script>
          jQuery(document).ready(function(){
            jQuery('ul.tabs').tabs('div.panes > div');
          });
        </script>");

  }

  function bible(){
    App::header()->addScript(App::url()->get('/bible.js','js'));
  }

  function bible_search(){
    $this->bible();
    App::header()->addCode("
        <script>
          var bad_request  = '". App::xlat('EXC_bad_request') ."';
          var ajax_loading = '". App::xlat('AJAX_loading') ."';
          var empty_search = '". App::xlat('AJAX_empty_search') ."';
          var route_bible  = '". App::xlat('route_bible') ."';
          var load_books_url   = '". App::base() . App::xlat('route_bible') ."load-books';
          var choose_an_option = '". App::xlat('AJAX_choose_an_option') ."';
        </script>
        ");
  }

  function bible_on_resize(){
    App::header()->addCode("
      <script>
        jQuery(window).resize(function() {
          set_element_position('div.opened','div.option',1);
        });
      </script>
    ");
  }

  function delayed_error($msg=null, $target=null, $seconds=500){
    if( empty($msg) || empty($target) ){
      return null;
    }

    App::header()->addCode("
      <script>
        jQuery(document).ready(function(){
          show_error('".$msg."','".$target."','".$seconds."');
        });
      </script>");
  }

  function poll(){
    App::header()->addCode("
        <script>
          var poll_on_error  = '". App::xlat('POLL_on_error') ."';
        </script>");
    App::header()->addScript( App::url()->get('/poll.js','js') );
  }

  function colorbox(){
    App::header()->addLink(App::skin('/css/colorbox.css'),array('rel'=>'stylesheet'));
    App::header()->addScript(App::url()->get('/jquery.colorbox-min-'. App::locale()->getLang() .'.js','js'));
  }

  function cBox_google_maps(){
    $this->colorbox();

    App::header()->addCode("
        <script>
          jQuery(document).ready(function () {
            $('.fMap').colorbox({width:'640',height:'480',iframe:true});
          });
        </script>");
  }

  function gallery(){
    $this->colorbox();
    App::header()->addLink(App::skin('/css/gallery.css'),array('rel'=>'stylesheet'));
    App::header()->addCode("
        <script>
          jQuery(document).ready(function(){
            $('a.cBox').colorbox({rel:'cBox'});
          });
        </script>");
  }

  function youtube_video_player(){
    $this->colorbox();
    App::header()->addCode("
        <script>
          jQuery(document).ready(function(){
            $('a.cBox_youtube').colorbox({iframe:true, innerWidth:640, innerHeight:480});
          });
        </script>");
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
    App::header()->addCode("
      <script>
        jQuery(document).ready(function(){

          jQuery('$tab').tabs('$content', {
            effect: 'fade',
            fadeOutSpeed: 0,
            fadeInSpeed: 500,
            onClick: function(event, index) {
              jQuery('$tab li').removeClass('vtab-selected');
              jQuery('a.current').parent().addClass('vtab-selected');
            }
          });

        });
      </script>");
  }
}