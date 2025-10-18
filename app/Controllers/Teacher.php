<?php

namespace App\Controllers;

class Teacher extends BaseController
{
	public function dashboard()
	{
		// require login
		if (! session()->get('isLoggedIn')) {
			session()->setFlashdata('error', 'Please login to access the teacher dashboard.');
			return redirect()->to('/auth/login');
		}

		// require teacher role
		if (session()->get('role') !== 'teacher') {
			session()->setFlashdata('error', 'Access denied.');
			return redirect()->to('/auth/login');
		}

		return view('teacher_dashboard');
	}
}
