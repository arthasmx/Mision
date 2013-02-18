<?php

if ( App::locale()->getLang() ==='es' ){
  $all_routes = array(
    array('route'=>'eventos/*', 'route_name'=>'list-events', 'controller'=>'events', 'action'=>'list'),
    array('route'=>'eventos/proximos-eventos/*', 'route_name'=>'next-event', 'controller'=>'events', 'action'=>'next'),
    array('route'=>'eventos/eventos-anteriores/*', 'route_name'=>'previous-event', 'controller'=>'events', 'action'=>'previous'),
    array('route'=>'evento/:seo', 'route_name'=>'read-event', 'controller'=>'events', 'action'=>'read'),

    array('route'=>'articulos/*', 'route_name'=>'list-articles', 'controller'=>'index', 'action'=>'list'),
    array('route'=>'articulo/:seo', 'route_name'=>'read-article', 'controller'=>'index', 'action'=>'read'),
    array('route'=>'negocios/*', 'route_name'=>'list-business', 'controller'=>'business', 'action'=>'list'),
    array('route'=>'negocio/:seo', 'route_name'=>'read-business', 'controller'=>'business', 'action'=>'read'),
    array('route'=>'anuncios/*', 'route_name'=>'list-anuncios', 'controller'=>'announcement', 'action'=>'list'),
    array('route'=>'anuncio/:seo', 'route_name'=>'read-anuncio', 'controller'=>'announcement', 'action'=>'read')
  );

  foreach($all_routes AS $route){

    $parsed_route = new Zend_Controller_Router_Route(
        $route['route'],
        array('module'     => 'Articles',
              'controller' => $route['controller'],
              'action'     => $route['action']));
    $router->addRoute($route['route_name'], $parsed_route);
  }

}