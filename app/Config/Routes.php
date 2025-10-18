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

// Authentication Routes
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('auth/dashboard', 'Auth::dashboard');

// Course Routes
$routes->get('courses', 'Course::index');
$routes->post('course/enroll', 'Course::enroll');
$routes->post('/course/enroll', 'Course::enroll');

// Admin - Course Management
$routes->get('admin/courses', 'Admin::courses');
$routes->post('admin/courses/store', 'Admin::storeCourse');

// Admin - Enrollment Management
$routes->get('admin/enrollments', 'Admin::enrollments');
$routes->post('admin/enrollments/store', 'Admin::storeEnrollment');

$routes->setAutoRoute(true);
