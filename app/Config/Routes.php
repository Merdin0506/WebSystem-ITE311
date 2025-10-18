<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('index', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication Routes (NO FILTER - public/semi-public)
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/dashboard', 'Auth::dashboard'); // Student dashboard

// Course Routes (accessible to all logged-in users)
$routes->get('courses', 'Course::index');
$routes->post('course/enroll', 'Course::enroll');


// Student routes - optional
$routes->group('student', ['filter' => 'roleauth'], function($routes) {
	$routes->get('dashboard', 'Auth::dashboard');
});

$routes->setAutoRoute(true);
