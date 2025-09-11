<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('auth', 'Auth::index');
$routes->get('auth/index', 'Auth::index');
$routes->get('auth/about', 'Auth::about');
$routes->get('auth/contact', 'Auth::contact');
$routes->setAutoRoute(true);
