<?php
$route = new Zend_Controller_Router_Route(
  'articles/:action/*',
  array('module'      => 'Articles',
        'controller'  => 'index',
        'action'      => 'list'));
$router->addRoute('article-section', $route);