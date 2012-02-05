<?php

// Rating
  $route = new Zend_Controller_Router_Route(
           'article/rate/:id/:rate',
           array( 'module'     => 'Addons',
                  'controller' => 'ajax',
                  'action'     => 'article-rate'));
  $router->addRoute('rate', $route);