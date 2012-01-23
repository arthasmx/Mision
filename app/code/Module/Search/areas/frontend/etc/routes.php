<?php

// Busquedas para biblia
  $route = new Zend_Controller_Router_Route(
    'biblia/buscar/:keyword/*',
    array('module'     => 'Search',
          'controller' => 'index',
          'action'     => 'bible-search') );
  $router->addRoute('bible_search', $route);

  // Busquedas para biblia
  $route = new Zend_Controller_Router_Route(
      ':type/buscar',
      array('module'    => 'Search',
          'controller'  => 'index',
          'action'      => 'search') );
  $router->addRoute('search', $route);
