<?php

$route = new Zend_Controller_Router_Route(
		':action/*',
    array(	'module'     => 'Default',
          	'controller'	=> 'index',
          	'action'     => 'index'));
$router->addRoute('public-section', $route);


$bible_params = array(  'module'     => 'Default',
                        'controller' => 'bible');
$bible_langs = array('en'=> "bible", 'es'=> 'biblia');

foreach($bible_langs AS $lang=>$value){
  $base = new Zend_Controller_Router_Route($value, array_push_assoc($bible_params, "action","index"));
  $book = new Zend_Controller_Router_Route($value . '/:book', array_push_assoc($bible_params, "action","book"));
  $cap  = new Zend_Controller_Router_Route($value . '/:book/:cap', array_push_assoc($bible_params, "action","cap"));
  $ver  = new Zend_Controller_Router_Route($value . '/:book/:cap/:ver/*', array_push_assoc($bible_params, "action","ver"));

  $router->addRoute('bible_base_'.$lang, $base);
  $router->addRoute('bible_book_'.$lang, $book);
  $router->addRoute('bible_cap_'.$lang,  $cap);
  $router->addRoute('bible_ver_'.$lang,  $ver);
}

// Fraternidades
  $route = new Zend_Controller_Router_Route(
  		'fraternities',
      array(	'module'     => 'Default',
            	'controller' => 'fraternity',
            	'action'     => 'index'));
  $router->addRoute('fraternity-all', $route);
  
  $route = new Zend_Controller_Router_Route(
  		'fraternidades',
      array(	'module'     => 'Default',
            	'controller' => 'fraternity',
            	'action'     => 'index'));
  $router->addRoute('fraternidad-all', $route);

  $route = new Zend_Controller_Router_Route(
  		'fraternidad/:gender',
      array(	'module'     => 'Default',
            	'controller' => 'fraternity',
            	'action'     => 'fraternity'));
  $router->addRoute('fraternidad-section', $route);

function array_push_assoc($array, $key, $value){
 $array[$key] = $value;
 return $array;
}