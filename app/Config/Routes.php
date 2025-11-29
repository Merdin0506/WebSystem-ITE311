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
$routes->get('courses/search', 'Course::search');
$routes->post('courses/search', 'Course::search');
$routes->options('courses/search', 'Course::search');
$routes->get('debug/courses', 'Debug::testCourses');
$routes->get('debug/auth', 'Debug::testAuth');
$routes->get('debug/search', 'Debug::testSearch');
$routes->get('diagnostic', function() { return view('diagnostic'); });
$routes->post('course/enroll', 'Course::enroll');
$routes->post('/course/enroll', 'Course::enroll');

// Announcements
$routes->get('announcements', 'Announcement::index');

// Notifications
$routes->get('notifications', 'Notifications::get');
$routes->post('notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');

// Admin routes - PROTECTED by RoleAuth filter
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
	$routes->get('dashboard', 'Admin::dashboard');
	$routes->get('courses', 'Admin::courses');
	$routes->post('courses/store', 'Admin::storeCourse');
	$routes->get('courses/delete/(:num)', 'Admin::deleteCourse/$1');
	$routes->get('enrollments', 'Admin::enrollments');
	$routes->post('enrollments/store', 'Admin::storeEnrollment');
	$routes->get('browse-uploads', 'Admin::browseUploads');
	$routes->get('materials', 'Admin::allMaterials');
});

// Teacher routes - PROTECTED by RoleAuth filter
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
	$routes->get('dashboard', 'Teacher::dashboard');
});

// Student routes - PROTECTED by RoleAuth filter
$routes->group('student', ['filter' => 'roleauth'], function($routes) {
	$routes->get('dashboard', 'StudentDashboard::dashboard');
	$routes->get('my-courses', 'StudentDashboard::myCourses');
	$routes->get('available-courses', 'StudentDashboard::availableCourses');
	$routes->get('course/(:num)/materials', 'StudentDashboard::courseMaterials/$1');
	$routes->get('assignments', 'StudentDashboard::assignments');
	$routes->get('grades', 'StudentDashboard::grades');
	$routes->get('announcements', 'StudentDashboard::announcements');
	$routes->get('notifications', 'StudentDashboard::notifications');
	$routes->post('mark-as-read', 'StudentDashboard::markAsRead');
	$routes->post('mark-all-as-read', 'StudentDashboard::markAllAsRead');
$routes->get('student/test-notifications', 'StudentDashboard::testNotifications');
});

// Security Testing Routes (for laboratory verification)
$routes->group('security-test', function($routes) {
    $routes->get('unauthorized', 'SecurityTest::testUnauthorized');
    $routes->get('sql-injection', 'SecurityTest::testSqlInjection');
    $routes->get('csrf', 'SecurityTest::testCsrf');
    $routes->get('data-tampering', 'SecurityTest::testDataTampering');
    $routes->get('input-validation', 'SecurityTest::testInputValidation');
});

// Materials Management Routes
$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/materials/processQuickUpload', 'Materials::processQuickUpload');
$routes->get('/admin/course/(:num)/materials', 'Materials::listByCourse/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');
$routes->get('/student/materials', 'Materials::studentMaterials');

// Admin API Routes
$routes->get('/admin/api/test', 'Admin::test');
$routes->get('/admin/api/courses', 'Admin::apiCourses');
$routes->get('/admin/api/materials', 'Admin::apiMaterials');

$routes->setAutoRoute(true);
