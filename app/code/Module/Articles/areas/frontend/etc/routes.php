<?php
$article_params = array( 'module'     => 'Articles',
                         'controller' => 'index');
$article_langs = array('en'=> "announcements", 'es'=> 'anuncios');

foreach($article_langs AS $lang=>$value){
  $base = new Zend_Controller_Router_Route($value . '/*', array_push_assoc($article_params, "action","announcement"));
  $read = new Zend_Controller_Router_Route($value . '/:seo', array_push_assoc($article_params, "action","read"));

  $router->addRoute('article_listing_'.$lang, $base);
  $router->addRoute('article_read_'.$lang, $read);
}