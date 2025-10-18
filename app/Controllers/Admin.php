<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Admin extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    public function dashboard()
    {
        // Require login
        if (! session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access the admin dashboard.');
            return redirect()->to(base_url('auth/login'));
        }

        // Require admin role
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to(base_url('announcements'));
        }

        // Fetch statistics with error handling
        $data = [
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'role' => session()->get('role')
            ],
            'totalUsers' => 0,
            'totalCourses' => 0,
            'totalEnrollments' => 0,
            'totalAnnouncements' => 0,
            'recentEnrollments' => []
        ];

        try {
            $data['totalUsers'] = $this->db->table('users')->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Admin dashboard - users count: ' . $e->getMessage());
        }

        try {
            $data['totalCourses'] = $this->db->table('courses')->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Admin dashboard - courses count: ' . $e->getMessage());
        }

        try {
            $data['totalEnrollments'] = $this->db->table('enrollments')->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Admin dashboard - enrollments count: ' . $e->getMessage());
        }

        try {
            $data['totalAnnouncements'] = $this->db->table('announcements')->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Admin dashboard - announcements count: ' . $e->getMessage());
        }

        try {
            $data['recentEnrollments'] = $this->db->table('enrollments e')
                ->select('e.*, u.name as student_name, c.course_name, c.title')
                ->join('users u', 'u.id = e.user_id', 'left')
                ->join('courses c', 'c.id = e.course_id', 'left')
                ->orderBy('e.created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Admin dashboard - recent enrollments: ' . $e->getMessage());
            $data['recentEnrollments'] = [];
        }

        return view('admin_dashboard', $data);
    }

    public function courses()
    {
        // Require login (you can tighten to admin-only if roles are set)
        if (! session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access admin.');
            return redirect()->to('/auth/login');
        }

        $courseModel = new CourseModel();

        // Fetch instructors list (all users for now; you can filter by role when available)
        $instructors = $this->db->table('users')->select('id, name, email')->get()->getResultArray();

        // Existing courses
        $courses = $courseModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/courses', [
            'instructors' => $instructors,
            'courses'     => $courses,
        ]);
    }

    public function storeCourse()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'title'         => 'required|min_length[3]|max_length[150]',
            'description'   => 'permit_empty|string',
            'instructor_id' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Validation failed.')->withInput();
        }

        $courseModel = new CourseModel();
        $data = [
            'title'         => $this->request->getPost('title'),
            'description'   => $this->request->getPost('description'),
            'instructor_id' => (int) $this->request->getPost('instructor_id'),
        ];

        if (! $this->db->table('users')->where('id', $data['instructor_id'])->get()->getRow()) {
            return redirect()->back()->with('error', 'Selected instructor does not exist.')->withInput();
        }

        if ($courseModel->insert($data) === false) {
            return redirect()->back()->with('error', 'Failed to create course.')->withInput();
        }

        return redirect()->to('/admin/courses')->with('success', 'Course created successfully.');
    }

    public function enrollments()
    {
        if (! session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access admin.');
            return redirect()->to('/auth/login');
        }

        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();

        // Fetch students and courses lists
        // If you implement roles, you can filter by role = 'student'
        $students = $this->db->table('users')->select('id, name, email')->orderBy('name', 'ASC')->get()->getResultArray();
        $courses  = $courseModel->orderBy('title', 'ASC')->findAll();

        // List recent enrollments with joins for display
        $enrollments = $this->db->table('enrollments e')
            ->select('e.id, e.enrollment_date, u.id as user_id, u.name as user_name, u.email as user_email, c.id as course_id, c.title as course_title')
            ->join('users u', 'u.id = e.user_id', 'left')
            ->join('courses c', 'c.id = e.course_id', 'left')
            ->orderBy('e.enrollment_date', 'DESC')
            ->limit(100)
            ->get()->getResultArray();

        return view('admin/enrollments', [
            'students'    => $students,
            'courses'     => $courses,
            'enrollments' => $enrollments,
        ]);
    }

    public function storeEnrollment()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'user_id'   => 'required|integer',
            'course_id' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Validation failed.')->withInput();
        }

        $userId   = (int) $this->request->getPost('user_id');
        $courseId = (int) $this->request->getPost('course_id');

        // Validate existence
        $user = $this->db->table('users')->where('id', $userId)->get()->getRow();
        if (! $user) {
            return redirect()->back()->with('error', 'Selected student does not exist.')->withInput();
        }
        $course = $this->db->table('courses')->where('id', $courseId)->get()->getRow();
        if (! $course) {
            return redirect()->back()->with('error', 'Selected course does not exist.')->withInput();
        }

        $enrollmentModel = new EnrollmentModel();
        if ($enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return redirect()->back()->with('error', 'Student is already enrolled in this course.');
        }

        $insertId = $enrollmentModel->enrollUser([
            'user_id'         => $userId,
            'course_id'       => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
        ]);

        if ($insertId === false) {
            return redirect()->back()->with('error', 'Failed to enroll student.');
        }

        return redirect()->to('/admin/enrollments')->with('success', 'Student enrolled successfully.');
    }
}
