<?php

namespace App\Controllers;

class Teacher extends BaseController
{
	protected $db;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
		helper(['url', 'form']);
	}

	public function dashboard()
	{
		// Require login
		if (! session()->get('isLoggedIn')) {
			session()->setFlashdata('error', 'Please login to access the teacher dashboard.');
			return redirect()->to(base_url('auth/login'));
		}

		// Require teacher role
		if (session()->get('role') !== 'teacher') {
			session()->setFlashdata('error', 'Access Denied: Insufficient Permissions');
			return redirect()->to(base_url('announcements'));
		}

		// Fetch teacher-specific data
		$data = [
			'user' => [
				'name' => session()->get('name'),
				'email' => session()->get('email'),
				'role' => session()->get('role')
			],
			'totalCourses' => 0,
			'totalStudents' => 0
		];

		try {
			$data['totalCourses'] = $this->db->table('courses')->countAllResults();
			$data['totalStudents'] = $this->db->table('enrollments')->countAllResults();
		} catch (\Exception $e) {
			log_message('error', 'Teacher dashboard error: ' . $e->getMessage());
		}

		return view('teacher_dashboard', $data);
	}
}
