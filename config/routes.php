<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
return array(
  'home' => array(
    'route' => '/',
    'options' => array(
      'controller' => 'static',
      'action' => 'home',
    ),
  ),
  'blog-index' => array(
    'route' => '/blog',
    'options' => array(
      'controller' => 'blog',
      'action' => 'index',
    ),
  ),
  'gallery-index' => array(
    'route' => '/gallery',
    'options' => array(
      'controller' => 'gallery',
      'action' => 'index',
    ),
  ),
  'gallery-category' => array(
    'route' => '/gallery/:category',
    'options' => array(
      'controller' => 'gallery',
      'action' => 'viewcategory',
      'parameters' => array(
        'category' => '(\w*)',
      ),
    ),
  ),
  'imprint' => array(
    'route' => '/imprint',
    'options' => array(
      'controller' => 'static',
      'action' => 'imprint',
    ),
  ),
);
