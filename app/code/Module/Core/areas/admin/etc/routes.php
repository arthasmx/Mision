<?php

// Carga de los estados
$route = new Zend_Controller_Router_Route( 'uploader/:action', array('module'     => 'Core',
                                                                     'controller' => 'Uploader'));
$router->addRoute('uploader', $route);


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
