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
			return redirect()->to('/auth/login');
		}

		$path = trim($request->getUri()->getPath(), '/');
		
		// Remove base path if present (e.g., ITE311-harid/public/)
		$path = preg_replace('#^[^/]*/public/#', '', $path);
		
		$prefix = explode('/', $path)[0] ?? '';

		// Allow announcements for all logged-in users
		if ($prefix === 'announcements' || $path === 'announcements') {
			return; // allowed
		}

		// Authorization rules based on route prefix
		if ($prefix === 'admin') {
			if ($role === 'admin') {
				return; // allowed
			}
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to('/announcements');
		}

		if ($prefix === 'teacher') {
			if ($role === 'teacher') {
				return; // allowed
			}
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to('/announcements');
		}

		if ($prefix === 'student') {
			if ($role === 'student') {
				return; // allowed
			}
			$session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to('/announcements');
		}

		// If route is /announcements allow (students allowed), otherwise allow by default
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
