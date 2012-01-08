<?php

$article_params = array( 'module'     => 'Articles',
                         'controller' => 'index');

$article_routes = array(
  'announcement' => array('en'=> "announcements", 'es'=> 'anuncios'),
  'events'       => array('en'=> "events", 'es'=> 'eventos')
);

foreach($article_routes AS $section=>$routes){

  foreach($routes AS $lang=>$value){
    $article_params["section"] = $value;
    $base = new Zend_Controller_Router_Route($value . '/*', array_push_assoc($article_params, "action", "list"  ));
    $read = new Zend_Controller_Router_Route($value . '/:seo', array_push_assoc($article_params, "action", ( $section=="events" ? "events":"read" )  ));

    $router->addRoute($section . '_article_listing_'.$lang, $base);
    $router->addRoute($section . '_article_read_'.$lang, $read);
  }

}
