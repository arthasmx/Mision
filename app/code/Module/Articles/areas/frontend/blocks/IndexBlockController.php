<?php

require_once 'Core/Controller/Block.php';

class Blog_IndexBlockController extends Core_Controller_Block {

	function init() {}

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