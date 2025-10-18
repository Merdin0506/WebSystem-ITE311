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
		$path = $request->getUri()->getPath();
		
		// Remove base path if present (e.g., /ITE311-harid/public/)
		$path = preg_replace('#^.*/public/#', '', $path);
		$path = trim($path, '/');
		
		// Get the first segment of the path
		$segments = explode('/', $path);
		$prefix = $segments[0] ?? '';

		// Allow /auth/* routes for all logged-in users (login, logout, dashboard, etc.)
		if ($prefix === 'auth') {
			return; // allowed
		}

		// Allow /announcements for all logged-in users
		if ($prefix === 'announcements' || $path === 'announcements') {
			return; // allowed
		}

		// Allow /courses for all logged-in users
		if ($prefix === 'courses' || $path === 'courses') {
			return; // allowed
		}

		// Admin authorization - can access /admin/*
		if ($prefix === 'admin') {
			if ($role === 'admin') {
				return; // allowed
			}
			// ACCESS DENIED - This is where the message is set
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to(base_url('announcements'));
		}

		// Teacher authorization - can access /teacher/*
		if ($prefix === 'teacher') {
			if ($role === 'teacher') {
				return; // allowed
			}
			// ACCESS DENIED - This is where the message is set
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to(base_url('announcements'));
		}

		// Student authorization - can access /student/* and /announcements
		if ($prefix === 'student') {
			if ($role === 'student' || $role === 'user') {
				return; // allowed
			}
			// ACCESS DENIED - This is where the message is set
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to(base_url('announcements'));
		}

		// Allow other routes (home, etc.)
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
