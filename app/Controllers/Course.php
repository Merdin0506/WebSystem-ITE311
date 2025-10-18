<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use CodeIgniter\HTTP\ResponseInterface;

class Course extends BaseController
{
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

        $userId = (int) session()->get('userID');
        $model  = new EnrollmentModel();

        // Check duplicate
        if ($model->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ]);
        }

        // Enroll user
        $insertId = $model->enrollUser([
            'user_id'         => $userId,
            'course_id'       => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
        ]);

        if ($insertId === false) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['success' => false, 'message' => 'Failed to enroll. Please try again.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Enrolled successfully.',
            'enrollment_id' => $insertId,
        ]);
    }
}
