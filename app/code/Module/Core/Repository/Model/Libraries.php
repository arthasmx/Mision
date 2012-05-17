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
        <script type='text/javascript'>
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
        <script type='text/javascript'>
          jQuery(document).ready(function(){
            jQuery('ul.tabs').tabs('div.panes > div');
          });
        </script>");

  }
}