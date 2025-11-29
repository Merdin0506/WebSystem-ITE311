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
            'recentEnrollments' => [],
            'courses' => []
        ];

        try {
            $data['totalUsers'] = $this->db->table('users')->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Admin dashboard - users count: ' . $e->getMessage());
        }

        try {
            $courseModel = new CourseModel();
            $courses = $courseModel->findAll();
            $data['courses'] = $courses;
            $data['totalCourses'] = count($courses);
        } catch (\Exception $e) {
            log_message('error', 'Admin dashboard - courses: ' . $e->getMessage());
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

        try {
            // Admin can see all recent materials from all courses (no enrollment required)
            $data['recentMaterials'] = $this->db->table('materials m')
                ->select('m.*, c.title as course_name')
                ->join('courses c', 'c.id = m.course_id', 'left')
                ->orderBy('m.created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Admin dashboard - recent materials: ' . $e->getMessage());
            $data['recentMaterials'] = [];
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

    /**
     * Simple test endpoint
     */
    public function test()
    {
        return $this->response->setJSON(['success' => true, 'message' => 'Admin controller is working']);
    }

    /**
     * API: Get all courses for AJAX requests
     */
    public function apiCourses()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        try {
            $courseModel = new CourseModel();
            $courses = $courseModel->findAll();

            // Transform the data to match expected format
            $transformedCourses = array_map(function($course) {
                return [
                    'id' => $course['id'],
                    'course_name' => $course['title'], // Map title to course_name
                    'course_code' => 'COURSE-' . str_pad($course['id'], 3, '0', STR_PAD_LEFT), // Generate course code
                    'title' => $course['title'],
                    'description' => $course['description']
                ];
            }, $courses);

            return $this->response->setJSON([
                'success' => true,
                'courses' => $transformedCourses
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Admin API courses error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading courses: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get all materials with course info for AJAX requests
     */
    public function apiMaterials()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        try {
            $query = $this->db->query("
                SELECT m.*, c.title as course_name, CONCAT('COURSE-', LPAD(c.id, 3, '0')) as course_code 
                FROM materials m 
                JOIN courses c ON c.id = m.course_id 
                ORDER BY m.created_at DESC
            ");
            
            $materials = $query->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'materials' => $materials
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Admin API materials error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading materials: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Browse uploaded files
     */
    public function browseUploads()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Access denied.');
        }

        $uploadPath = FCPATH . 'uploads/materials';
        $files = [];
        
        if (is_dir($uploadPath)) {
            $fileList = scandir($uploadPath);
            foreach ($fileList as $file) {
                if ($file != '.' && $file != '..' && is_file($uploadPath . '/' . $file)) {
                    $filePath = $uploadPath . '/' . $file;
                    $files[] = [
                        'name' => $file,
                        'size' => filesize($filePath),
                        'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'path' => 'uploads/materials/' . $file
                    ];
                }
            }
            // Sort by date (newest first)
            usort($files, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }

        $data = [
            'title' => 'Browse Uploaded Files',
            'files' => $files,
            'uploadPath' => $uploadPath
        ];

        return view('admin/browse_uploads', $data);
    }

    /**
     * View all materials from all courses (Admin access)
     */
    public function allMaterials()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Access denied.');
        }

        $materialModel = new \App\Models\MaterialModel();

        // Get all materials with course information - Admin has full access
        $materials = $materialModel->select('materials.*, courses.title as course_name, CONCAT("COURSE-", LPAD(courses.id, 3, "0")) as course_code')
                    ->join('courses', 'courses.id = materials.course_id')
                    ->orderBy('materials.created_at', 'DESC')
                    ->findAll();

        $data = [
            'title' => 'All Course Materials',
            'materials' => $materials
        ];

        return view('admin/materials', $data);
    }

    /**
     * Delete course and all related data
     */
    public function deleteCourse($course_id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Access denied.');
        }

        try {
            $courseModel = new CourseModel();
            $course = $courseModel->find($course_id);
            
            if (!$course) {
                return redirect()->to('/admin/courses')->with('error', 'Course not found.');
            }

            // Start transaction
            $this->db->transStart();

            // Delete related materials files from filesystem
            $materialModel = new \App\Models\MaterialModel();
            $materials = $materialModel->getMaterialsByCourse($course_id);
            
            foreach ($materials as $material) {
                $filePath = FCPATH . $material['file_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete materials from database
            $materialModel->where('course_id', $course_id)->delete();

            // Delete enrollments
            $enrollmentModel = new EnrollmentModel();
            $enrollmentModel->where('course_id', $course_id)->delete();

            // Delete the course
            $courseModel->delete($course_id);

            // Complete transaction
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return redirect()->to('/admin/courses')->with('error', 'Failed to delete course. Please try again.');
            }

            return redirect()->to('/admin/courses')->with('success', 'Course "' . $course['title'] . '" and all related data have been successfully deleted.');

        } catch (\Exception $e) {
            log_message('error', 'Course deletion error: ' . $e->getMessage());
            return redirect()->to('/admin/courses')->with('error', 'An error occurred while deleting the course.');
        }
    }
}
