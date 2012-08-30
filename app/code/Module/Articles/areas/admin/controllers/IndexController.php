<?php
require_once 'Module/Articles/Controller/Action/Admin.php';

class Articles_IndexController extends Module_Articles_Controller_Action_Admin {

	function preDispatch(){
	  App::module('Core')->getModel('Libraries')->articles();
	}

	function listAction() {
	  $this->view->current_menu = array('menu'=>3,'sub'=>4);

    $core_abstract          = $this->_module->getModel('Sorterfilter')->admin_sort_rules();
    $this->view->datasorter = $core_abstract->datasorter_to_render();

    $this->view->articles   = $this->_module->getModel('Article')->admin_list( $core_abstract );

	}

	function statusAction(){
	  $this->designManager()->setCurrentLayout('ajax');
	  echo $this->_module->getModel('Cud/Articles')->stat( $this->getRequest()->getParam('article_id'), $this->getRequest()->getParam('status') );
	  exit;
	}

	function addAction(){
    $this->view->current_menu = array('menu'=>3,'sub'=>5);
    $libraries                = App::module('Core')->getModel('Libraries');

    // Tab 1 content
    $libraries->jquery_tools_wizard_tabs();
    $libraries->jquery_date_picker( array('event_date', 'publicate_at') );
    $libraries->jquery_preview( array('p_title'=>'input#title', 'p_description'=>'textarea#description') );
    $libraries->ckeditor('article',array('toolbar'=>'articleCreate', 'height'=>'43.3em', 'language'=>App::locale()->getLang() ));
    $libraries->append_form_controls(array('table#youtube','table#links'));
    $libraries->uploader(array('gallery','audio','files'), array('/uploader/article-images', '/uploader/article-audio', '/uploader/article-files'));

    $this->view->basic_data      = $this->_module->getModel('Forms/BasicData')->get();
    $this->view->article_content = $this->_module->getModel('Forms/ArticleContent')->get();

    // Tab 2 content
//




	}

}