<?php
require_once 'Core/Controller/Block.php';
class Addons_Site_IndexBlockController extends Core_Controller_Block {

  function init() {}

  function getSiteEmailManagerAction(){}

  function mapAction(){
    $config = $this->_module->getConfig('core', 'map');

    $coordinates = $this->getParam('coordinates');
    $zoom       = $this->getParam('zoom');
    $key        = $this->getParam('key');
    $url        = $this->getParam('url');
    $width      = $this->getParam('width');
    $height     = $this->getParam('height');
    $language   = $this->getParam('language');
    $alt        = $this->getParam('alt');
    $picture    = $this->getParam('picture');

    $this->view->coordinates = empty($coordinates) ? $config['coordinates']    : $this->getParam('coordinates');
    $this->view->zoom       = empty($zoom)       ? $config['zoom']          : $this->getParam('zoom');
    $this->view->key        = empty($key)        ? $config['key']           : $this->getParam('key');
    $this->view->url        = empty($url)        ? $config['url']           : $this->getParam('url');
    $this->view->width      = empty($width)      ? $config['width']         : $this->getParam('width');
    $this->view->height     = empty($height)     ? $config['height']        : $this->getParam('height');
    $this->view->language   = empty($language)   ? App::locale()->getLang() : $this->getParam('language');
    $this->view->picture    = empty($picture)    ? $config['picture']       : $this->getParam('picture');
    $this->view->alt        = empty($alt)        ? App::xlat('MAP_description') : $this->getParam('alt');

    App::module('Core')->getModel('Libraries')->cBox_google_maps();
  }

  function socialNetworksAction(){}

}