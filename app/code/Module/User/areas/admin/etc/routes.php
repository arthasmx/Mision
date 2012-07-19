<?php
  $route = new Zend_Controller_Router_Route(
    ':action',
    array(
        'module'	   => 'User',
        'controller' => 'index'
  ));
$router->addRoute('admin', $route);
