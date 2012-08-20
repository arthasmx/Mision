<?php
require_once 'Core/Controller/Block.php';
class Articles_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function sliderAction(){
    $this->view->gallery_path    = App::module('Addons')->getModel('Gallery')->get_gallery_base_path();
    $this->view->coming_next     = App::module('Articles')->getModel('Article')->get_articles_for_content_slider( $this->getParam("category"), $this->getParam('past_next') );

    App::header()->addLink(App::skin('/css/DDSlider.css'),array(
        "rel"=>"stylesheet",
        "type"=>"text/css",
        "media"=>"all",
    ));

    App::header()->addScript( App::url()->get('/jquery.easing.1.3.js','js') );
    App::header()->addScript( App::url()->get('/jquery.DDSlider.min.js','js') );

    App::header()->addCode("
        <script>
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

  function promoteAction(){
    $width              = $this->getParam('width');
    $height             = $this->getParam('height');
    $limit              = $this->getParam('limit');
    $this->view->width  = empty($width)  ? $this->_module->getConfig('core','promote_block_width')  : $this->getParam('width');
    $this->view->height = empty($height) ? $this->_module->getConfig('core','promote_block_height') : $this->getParam('height');
    $limit              = empty($limit)  ? $this->_module->getConfig('core','promote_block_limit')  : $limit;
    $this->view->gallery_path = App::module('Addons')->getModel('Gallery')->get_gallery_base_path();

    $this->view->events = App::module('Articles')->getModel('Article')->get_articles_for_content_slider( $this->getParam("category"), $this->getParam('past_next'), $limit );

    App::header()->addLink(App::skin('/css/tabs-slideshow.css'),array(
        "rel"=>"stylesheet",
        "type"=>"text/css",
        "media"=>"all",
    ));

    App::header()->addScript( App::url()->get('/jquery.tools.min.js','js') );
    App::header()->addCode("
        <script>
          jQuery(document).ready(function(){
            jQuery('.slidetabs').tabs('.images > div', {
              effect: 'fade',
              fadeOutSpeed: 'slow',
              rotate: true
            }).slideshow({clickable:false, autoplay:true});
          });
        </script>
    ");
  }

  function shepherdWelcomeAction(){
    $this->view->article = $this->_module->getModel('Article')->get_article( App::xlat('shepherd_welcome_article_details_id') );
  }

  function radioAction(){
    $this->view->article = $this->_module->getModel('Article')->get_article_basic_data( 'radio' );
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