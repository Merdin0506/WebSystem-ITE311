<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleAuth implements FilterInterface
{
	/**
	 * Run before the request is handled.
	 *
	 * @param RequestInterface $request
	 * @param array|null       $arguments
	 * @return mixed
	 */
	public function before(RequestInterface $request, $arguments = null)
	{
		$session = session();
		$logged = $session->get('isLoggedIn');
		$role = $session->get('role');

		// If not logged in, redirect to login
		if (! $logged) {
			$session->setFlashdata('error', 'Please login to continue.');
			return redirect()->to(base_url('auth/login'));
		}

		// Get the current path
		$uri = $request->getUri();
		$path = $uri->getPath();
		
		// Clean the path - remove base folder and public
		$path = str_replace('/ITE311-harid/public/', '', $path);
		$path = str_replace('/ITE311-harid/', '', $path);
		$path = trim($path, '/');
		
		// Get the first segment
		$segments = explode('/', $path);
		$prefix = $segments[0] ?? '';

		// Allow these routes for ALL logged-in users
		$allowedForAll = ['auth', 'announcements', 'courses', 'course', 'home', 'index', 'about', 'contact', ''];
		if (in_array($prefix, $allowedForAll)) {
			return; // allowed
		}

		// STRICT ROLE-BASED ACCESS CONTROL - BLOCK unauthorized access
		
		// ADMIN: Can only access /admin/* routes
		if ($prefix === 'admin') {
			if ($role === 'admin') {
				return; // allowed
			}
			// BLOCK: Admin routes for non-admins
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to(base_url('announcements'));
		}

		// TEACHER: Can only access /teacher/* routes
		if ($prefix === 'teacher') {
			if ($role === 'teacher') {
				return; // allowed
			}
			// BLOCK: Teacher routes for non-teachers (including admins)
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to(base_url('announcements'));
		}

		// STUDENT: Can only access /student/* routes
		if ($prefix === 'student') {
			if ($role === 'student' || $role === 'user') {
				return; // allowed
			}
			// BLOCK: Student routes for non-students
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to(base_url('announcements'));
		}

		// Default: allow other routes
		return;
	}

	/**
	 * Run after the request is handled.
	 */
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// no-op
	}
}
