<?php

require_once "Local/View/Helper/Paginate.php";
class Local_View_Helper_Ajaxpaginate extends Local_View_Helper_Paginate {

	var $jscallback=null;

	public function setJscallback($jscallback) {
		$this->jscallback=str_replace("'",'"',$jscallback);
		return $this;
	}

    public function ajaxpaginate() {

    	// CSS
			//App::header()->addLink(App::skin('/css/blocks/dataset.css'),array('rel'=>'stylesheet','type'=>'text/css'));
			//App::header()->addLink(App::skin('/css/blocks/paginate.css'),array('rel'=>'stylesheet','type'=>'text/css'));

    	// Intentamos añadir las traducciones de la paginación
	    	if (class_exists("App") && method_exists('App',"translate")) {
	    		App::translate()->addGlobalTranslate('paginate');
	    	}
		return $this;
    }


	/**
	 * Muestra el detalle de las páginas
	 *
	 */
    public function renderPages() {
	    	// Comprobaciones
	    		if (!$this->page_render) {
	    			echo "# Error: No se ha especificado el numero de paginas a mostrar.";return;
	    		}

	    	// Si no hay resultados regresamos
	    		if ($this->items_total<1) {echo App::xlat('Ninguna');return;}

	    	// Cargamos array de páginas a mostrar
	    		$pages=$this->getPages();

	    	// Renderizado por defecto, se puede realizar un renderizado personalizado en la vista llamando directamente al método
	    	// getPages() y procesando el array devuelto como se desee.
	    		foreach ($pages as $key=>$page) {
	    			if ('pages'===$key) {
	    				// Procesamos páginas numéricas
	    					//echo "<span class='numeric-pages'>";
	    						foreach ($page as $numeric_page) {
	    							if (!$numeric_page['current']) {
		    							printf(
		    								//"<span class='page numeric'><a href='' onclick='%s'>%s</a></span>",
		    								"<a class='paginator' href='' onclick='%s'>%s</a>",
		    								str_replace("url", $this->getUrl()."/".str_replace(":page",$numeric_page['page'],$this->page_param_tpl) , $this->jscallback),
		    								$numeric_page['title']
		    							);
	    							} else {
	    								printf(
		    								//"<span class='page numeric current'><a href='' onclick='%s'>%s</a></span>",
		    								"<a class='paginator current' href='' onclick='%s'>%s</a>",
		    								str_replace("url", $this->getUrl()."/".str_replace(":page",$numeric_page['page'],$this->page_param_tpl) , $this->jscallback),
		    								$numeric_page['title']
		    							);
	    							}
	    						}
	    					//echo "</span>";
	    			} else {
	    				// Procesamos página de texto
							printf(
									//"<span class='page text'><a href='' onclick='%s'>%s</a></span>" ,
									"<a class='paginator-text' href='' onclick='%s'>%s</a>",
									 str_replace("url", $this->getUrl()."/".str_replace(":page",$page['page'],$this->page_param_tpl) , $this->jscallback),
									 $page['title']
							);
	    			}
	    		}
    }


}
?>