<?php

// Rating
  $route = new Zend_Controller_Router_Route(
           'article/rate/:id/:rate',
           array( 'module'     => 'Addons',
                  'controller' => 'ajax',
                  'action'     => 'article-rate'));
  $router->addRoute('rate', $route);

  // Poll
  $route = new Zend_Controller_Router_Route(
           'poll/vote/:id/:vote',
           array( 'module'     => 'Addons',
                  'controller' => 'ajax',
                  'action'     => 'poll-vote'));
  $router->addRoute('vote', $route);