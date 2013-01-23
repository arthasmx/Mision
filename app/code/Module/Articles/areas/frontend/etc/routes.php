<?php

$article_params = array( 'module'     => 'Articles',
                         'controller' => 'index');

$article_routes = array(
  'announcement' => array('en'=> "announcements", 'es'=> 'anuncios'),
  'events'       => array('en'=> "events", 'es'=> 'eventos')
);

foreach($article_routes AS $section=>$routes){

  foreach($routes AS $lang=>$value){
    $article_params["section"] = $section;
    $base = new Zend_Controller_Router_Route($value . '/*', array_push_assoc($article_params, "action", "list-" . $section  ));
    $read = new Zend_Controller_Router_Route($value . '/:seo/*', array_push_assoc($article_params, "action", "read-" . $section  ));

    $router->addRoute($section . '_article_listing_'.$lang, $base);
    $router->addRoute($section . '_article_read_'.$lang, $read);
  }

}

$basic_article = new Zend_Controller_Router_Route('articulo/:seo/*', array_push_assoc($article_params, "action", "read"));
$router->addRoute('basic_article', $basic_article);


$route = new Zend_Controller_Router_Route(
    'evento/:seo/*',
    array('module'   => 'Articles',
        'controller' => 'events',
        'action'     => 'read'));
$router->addRoute('reading-event', $route);