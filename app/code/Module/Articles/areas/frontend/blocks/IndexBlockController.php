<?php
require_once 'Core/Controller/Block.php';
class Articles_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function comingNextAction(){
    $next                        = $this->getParam('next');
    $this->view->gallery_path    = App::module('Addons')->getModel('Gallery')->get_gallery_base_path();
    $this->view->coming_next     = App::module('Articles')->getModel('Article')->get_articles_for_content_slider( $this->getParam("type"), $next );

    if( ! empty($next) ){
      $this->view->coming_soon = true;
    }

    App::header()->addLink(App::skin('/css/DDSlider.css'),array(
        "rel"=>"stylesheet",
        "type"=>"text/css",
        "media"=>"all",
    ));

    App::header()->addScript( App::url()->get('/jquery.easing.1.3.js','js') );
    App::header()->addScript( App::url()->get('/jquery.DDSlider.min.js','js') );

    App::header()->addCode("
        <script type='text/javascript'>
          jQuery(document).ready(function(){
            jQuery('#ddSlider').DDSlider({
              trans:     'fading',
              nextSlide : '.slider_arrow_right',
              prevSlide : '.slider_arrow_left',
              selector  : '.slider_selector'
            });
          });
        </script>
        ");

  }

	/**
	 * Bloque que muestra los últimos casos de éxito
	 *
	 */
	function getlatestAction() {
		// Cargamos el CSS para mostrar las estrellitas
			App::header()->addLink(App::skin('/css/pages/rate.css'),array(
				"rel"=>"stylesheet",
				"type"=>"text/css",
				"media"=>"all",
			));

		// Sacamos los articulos
			$this->view->blog=$this->_module->getModelSingleton('blog')->getLatest();

	}

}