<?php
// Basic
$route = new Zend_Controller_Router_Route(
		':action/*',
    array(	'module' => 'Default',
		'controller' => 'index',
		'action'     => 'index'));
$router->addRoute('public-section', $route);

// Biblia
$bible_params = array(  'module'     => 'Default',
                        'controller' => 'bible');
$bible_langs = array('en'=> "bible", 'es'=> 'biblia');

foreach($bible_langs AS $lang=>$value){
  $base     = new Zend_Controller_Router_Route($value, array_push_assoc($bible_params, "action","index"));
  $book     = new Zend_Controller_Router_Route($value . '/:book', array_push_assoc($bible_params, "action","book"));
  $chapter  = new Zend_Controller_Router_Route($value . '/:book/:chapter', array_push_assoc($bible_params, "action","chapter"));
  $verse    = new Zend_Controller_Router_Route($value . '/:book/:chapter/:verse/*', array_push_assoc($bible_params, "action","verse"));
  $load_books = new Zend_Controller_Router_Route($value . '/load-books', array_push_assoc($bible_params, "action","load-books"));

  $router->addRoute('bible_base_'.$lang, $base);
  $router->addRoute('bible_book_'.$lang, $book);
  $router->addRoute('bible_chapter_'.$lang,  $chapter);
  $router->addRoute('bible_verse_'.$lang,  $verse);
  $router->addRoute('bible_load_'.$lang,  $load_books);
}

// Multimedia
  $route = new Zend_Controller_Router_Route(
  		'multimedia',
      array(	'module'     => 'Default',
            	'controller' => 'multimedia',
            	'action'     => 'index'));
  $router->addRoute('multimedia-all', $route);

  $route = new Zend_Controller_Router_Route(
 			'multimedia/:media',
      array(	'module'     => 'Default',
            	'controller' => 'multimedia',
            	'action'     => 'media'));
  $router->addRoute('multimedia-section', $route);

  $route = new Zend_Controller_Router_Route(
      'descargar/audio/:folder/:year/:month/:file',
      array( 'module'     => 'Default',
             'controller' => 'index',
             'action'     => 'audio-download'));
  $router->addRoute('download', $route);

  $route = new Zend_Controller_Router_Route(
      'download/:date/:type/:id/:reference',
      array( 'module'     => 'Default',
          'controller' => 'index',
          'action'     => 'download-article-addon'));
  $router->addRoute('downloads', $route);


  // Flexar
  $route = new Zend_Controller_Router_Route(
      'flexar',
      array( 'module'     => 'Default',
             'controller' => 'index',
             'action'     => 'flexar'));
  $router->addRoute('flexar', $route);

function array_push_assoc($array, $key, $value){
 $array[$key] = $value;
 return $array;
}