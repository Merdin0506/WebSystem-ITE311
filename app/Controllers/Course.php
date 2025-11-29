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

        $courses = $courseModel->orderBy('created_at', 'DESC')->findAll();

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
}
