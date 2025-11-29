<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use CodeIgniter\HTTP\ResponseInterface;

class Course extends BaseController
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    /**
     * Display available courses and whether the logged-in student is enrolled.
     */
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to view courses.');
            return redirect()->to('/auth/login');
        }

        $courseModel = new CourseModel();
        $enrollModel = new EnrollmentModel();
        $userId = (int) session()->get('userID');

        // Get courses with instructor names
        $courses = $this->db->table('courses')
            ->select('courses.*, users.name as instructor_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->orderBy('courses.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Build enrolled set for quick lookup
        $userEnrollments = $enrollModel->where('user_id', $userId)->findAll();
        $enrolledCourseIds = array_column($userEnrollments, 'course_id');

        return view('courses/index', [
            'courses' => $courses,
            'enrolledCourseIds' => $enrolledCourseIds,
        ]);
    }
    /**
     * Handle AJAX enrollment requests
     * POST: course_id
     *
     * Returns JSON: { success: bool, message: string }
     */
    public function enroll()
    {
        // Must be AJAX
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON(['success' => false, 'message' => 'Invalid request type.']);
        }

        // Must be logged in
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON(['success' => false, 'message' => 'Please login to enroll.']);
        }

        // Validate input
        $courseId = (int) $this->request->getPost('course_id');
        if ($courseId <= 0) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON(['success' => false, 'message' => 'Invalid course.']);
        }

        $userId = session()->get('userID');
        $enrollmentModel = new EnrollmentModel();
        $courseModel = new CourseModel();

        // Check if course exists
        $course = $courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['success' => false, 'message' => 'Course not found.']);
        }

        // Check if already enrolled
        $existingEnrollment = $enrollmentModel->where('user_id', $userId)
                                            ->where('course_id', $courseId)
                                            ->first();

        if ($existingEnrollment) {
            log_message('debug', "Enroll: User $userId already enrolled in course $courseId");
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ]);
        }

        // Try to enroll
        try {
            $enrollData = [
                'user_id' => $userId,
                'course_id' => $courseId,
                'enrollment_date' => date('Y-m-d H:i:s')
            ];
            
            $enrollmentId = $enrollmentModel->insert($enrollData);

            if ($enrollmentId) {
                // Create notification
                $message = 'You have been enrolled in ' . $course['title'];
                $notificationModel = new \App\Models\NotificationModel();
                $notifId = $notificationModel->insert([
                    'user_id' => $userId,
                    'message' => $message,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                log_message('debug', "Enroll: Notification $notifId created for user $userId after enrolling in course $courseId");

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Enrollment successful! You are now enrolled in ' . $course['title'] . '.'
                ]);
            } else {
                log_message('error', "Enroll: Failed to insert enrollment for user $userId course $courseId");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Enrollment failed. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', "Enroll: Exception - " . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred during enrollment: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Search courses functionality
     * Accepts both GET and POST requests with search term
     * Returns JSON for AJAX requests or renders view for regular requests
     */
    public function search()
    {
        // Set CORS headers for AJAX requests
        $this->response->setHeader('Access-Control-Allow-Origin', 'http://localhost:8080');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Authorization');
        $this->response->setHeader('Access-Control-Allow-Credentials', 'true');

        // Handle OPTIONS preflight request
        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to search courses.'
                ]);
            }
            return redirect()->to('/auth/login')->with('error', 'Please login to search courses.');
        }

        $searchTerm = $this->request->getVar('search') ?? $this->request->getPost('search');
        $courseModel = new CourseModel();
        $enrollModel = new EnrollmentModel();
        $userId = (int) session()->get('userID');

        // Debug logging
        log_message('debug', 'Course search initiated. Search term: ' . ($searchTerm ?? 'empty') . ', User ID: ' . $userId);

        if (!empty($searchTerm)) {
            // Use Query Builder to search courses with instructor names using LIKE queries
            $courses = $this->db->table('courses')
                ->select('courses.*, users.name as instructor_name')
                ->join('users', 'users.id = courses.instructor_id', 'left')
                ->groupStart()
                    ->like('courses.title', $searchTerm)
                    ->orLike('courses.description', $searchTerm)
                    ->orLike('users.name', $searchTerm)
                ->groupEnd()
                ->orderBy('courses.created_at', 'DESC')
                ->get()
                ->getResultArray();
        } else {
            // If no search term, return all courses with instructor names
            $courses = $this->db->table('courses')
                ->select('courses.*, users.name as instructor_name')
                ->join('users', 'users.id = courses.instructor_id', 'left')
                ->orderBy('courses.created_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        // Debug logging
        log_message('debug', 'Course search results: Found ' . count($courses) . ' courses');

        // Get enrolled course IDs for the current user
        $userEnrollments = $enrollModel->where('user_id', $userId)->findAll();
        $enrolledCourseIds = array_column($userEnrollments, 'course_id');

        // Check if this is an AJAX request
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'courses' => $courses,
                'enrolledCourseIds' => $enrolledCourseIds,
                'searchTerm' => $searchTerm,
                'totalResults' => count($courses),
                'debug' => [
                    'search_term' => $searchTerm,
                    'user_id' => $userId,
                    'course_count' => count($courses)
                ]
            ]);
        }

        // For regular requests, render the view
        return view('courses/index', [
            'courses' => $courses,
            'enrolledCourseIds' => $enrolledCourseIds,
            'searchTerm' => $searchTerm ?? ''
        ]);
    }
}
