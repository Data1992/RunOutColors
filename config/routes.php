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
  'artist' => array(
    'route' => '/the-artist',
    'options' => array(
      'controller' => 'static',
      'action' => 'artist',
    ),
  ),
  'blog-index' => array(
    'route' => '/blog',
    'options' => array(
      'controller' => 'blog',
      'action' => 'index',
    ),
  ),
  'blog-index-paged' => array(
    'route' => '/blog/page/:page',
    'options' => array(
      'controller' => 'blog',
      'action' => 'index',
      'parameters' => array(
        'page' => 'numeric',
      ),
    ),
  ),
  'blog-view' => array(
    'route' => '/blog/article/:id',
    'options' => array(
      'controller' => 'blog',
      'action' => 'view',
      'parameters' => array(
        'id' => 'numeric',
      ),
    ),
  ),
  'gallery-index' => array(
    'route' => '/gallery',
    'options' => array(
      'controller' => 'gallery',
      'action' => 'index',
    ),
  ),
  'gallery-download' => array(
    'route' => '/gallery/:category/download/:id',
    'options' => array(
      'controller' => 'gallery',
      'action' => 'downloadimage',
      'parameters' => array(
        'category' => '([\w-]*)',
        'id' => 'numeric',
      ),
    ),
  ),
  'gallery-image' => array(
    'route' => '/gallery/:category/:id',
    'options' => array(
      'controller' => 'gallery',
      'action' => 'viewimage',
      'parameters' => array(
        'category' => '([\w-]*)',
        'id' => 'numeric',
      ),
    ),
  ),
  'gallery-category' => array(
    'route' => '/gallery/:category',
    'options' => array(
      'controller' => 'gallery',
      'action' => 'viewcategory',
      'parameters' => array(
        'category' => '([\w-]*)',
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
