<?php

// Carga de imagenes
$route = new Zend_Controller_Router_Route( 'gallery/:action', array('module'     => 'Addons',
                                                                     'controller' => 'Gallery'));
$router->addRoute('gallery_admin', $route);


	// Carga de los estados
    $route = new Zend_Controller_Router_Route(
        'site/states',
        array(
            'module'        => 'Core',
            'controller'    => 'World',
            'action'        => 'states',
        )
    );
    $router->addRoute('estados', $route);

  // Categories Route
  $route = new Zend_Controller_Router_Route(
      'categories/:action/*',
      array('module'      => 'Addons',
            'controller'  => 'Category',
            'action'      => 'list'));
  $router->addRoute('categories-section', $route);

  $route = new Zend_Controller_Router_Route(
      'categories/edit/:parent/*',
      array('module'    => 'Addons',
          'controller'  => 'Category',
          'action'      => 'edit'));
  $router->addRoute('categories-edit', $route);