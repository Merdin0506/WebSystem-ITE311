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

// Announcements
$routes->get('announcements', 'Announcement::index');

// Admin routes - PROTECTED by RoleAuth filter
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
	$routes->get('dashboard', 'Admin::dashboard');
	$routes->get('courses', 'Admin::courses');
	$routes->post('courses/store', 'Admin::storeCourse');
	$routes->get('enrollments', 'Admin::enrollments');
	$routes->post('enrollments/store', 'Admin::storeEnrollment');
});

// Teacher routes - PROTECTED by RoleAuth filter
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
	$routes->get('dashboard', 'Teacher::dashboard');
});

// Student routes - PROTECTED by RoleAuth filter
$routes->group('student', ['filter' => 'roleauth'], function($routes) {
	$routes->get('dashboard', 'Auth::dashboard');
});

$routes->setAutoRoute(true);
