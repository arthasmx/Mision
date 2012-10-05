<?php
$route = new Zend_Controller_Router_Route(
  'articles/:action/*',
  array('module'      => 'Articles',
        'controller'  => 'index',
        'action'      => 'list'));
$router->addRoute('article-section', $route);

$route = new Zend_Controller_Router_Route( 'articles/edit/:id', array('module'      => 'Articles', 'controller'  => 'index', 'action' => 'edit'));
$router->addRoute('article-edit', $route);

$route = new Zend_Controller_Router_Route(
    'articles-up/:action/*',
    array('module'    => 'Articles',
        'controller'  => 'uploads'));
$router->addRoute('article-uploads', $route);

$route = new Zend_Controller_Router_Route(
    'articles-files-paginate/:action/:page',
    array('module'    => 'Articles',
        'controller'  => 'files'));
$router->addRoute('paginate-local-files', $route);

$route = new Zend_Controller_Router_Route(
    'articles-fi/:action',
    array('module'    => 'Articles',
        'controller'  => 'files'));
$router->addRoute('article-files', $route);