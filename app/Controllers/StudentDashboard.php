<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\MaterialModel;
use App\Models\NotificationModel;

class StudentDashboard extends BaseController
{
    protected $db;
    protected $session;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = session();
        helper(['form', 'url']);
    }

    /**
     * Check if user is logged in and is a student
     */
    private function checkAccess()
    {
        if (!$this->session->get('isLoggedIn')) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to('/auth/login');
        }

        $role = $this->session->get('role');
        if ($role !== 'student' && $role !== 'user') {
            $this->session->setFlashdata('error', 'Access denied. Students only.');
            return redirect()->to('/student/dashboard');
        }

        return null;
    }

    /**
     * Load notification data for the user
     */
    private function loadNotificationData($userId)
    {
        try {
            $notificationModel = new NotificationModel();
            
            // Get fresh data from database (no caching)
            $notifications = $notificationModel->getNotificationsForUser($userId);
            $unreadCount = $notificationModel->getUnreadCount($userId);
            
            // Debug logging
            log_message('debug', 'Loading notification data for user ' . $userId . ': ' . count($notifications) . ' unread notifications, total unread count: ' . $unreadCount);
            
            return [
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ];
        } catch (\Exception $e) {
            log_message('error', 'Notification loading error: ' . $e->getMessage());
            return [
                'notifications' => [],
                'unread_count' => 0
            ];
        }
    }

    /**
     * Main dashboard view
     */
    public function dashboard()
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        // Prevent caching to ensure fresh notification data
        $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');

        $userId = (int) $this->session->get('userID');
        $data = [
            'user' => [
                'id' => $userId,
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'activeMenu' => 'dashboard'
        ];
        
        // Load notification data
        $notificationData = $this->loadNotificationData($userId);
        $data = array_merge($data, $notificationData);

        // Get dashboard statistics
        try {
            $enrollmentModel = new EnrollmentModel();
            $announcementModel = new AnnouncementModel();
            $notificationModel = new NotificationModel();
            
            // Get enrolled courses count
            $enrollments = $enrollmentModel->getUserEnrollments($userId);
            $data['stats'] = [
                'enrolled_courses' => count($enrollments),
                'total_announcements' => $announcementModel->countAll(),
                'recent_enrollments' => array_slice($enrollments, 0, 3) // Show latest 3
            ];

            // Get recent announcements
            $data['recent_announcements'] = $announcementModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
            
        } catch (\Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());
            $data['stats'] = ['enrolled_courses' => 0, 'total_announcements' => 0, 'recent_enrollments' => []];
            $data['recent_announcements'] = [];
        }

        return view('student/dashboard', $data);
    }

    /**
     * My Courses - Show enrolled courses
     */
    public function myCourses()
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        // Prevent caching to ensure fresh notification data
        $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');

        $userId = (int) $this->session->get('userID');
        $data = [
            'user' => [
                'id' => $userId,
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'activeMenu' => 'my-courses'
        ];
        
        // Load notification data
        $notificationData = $this->loadNotificationData($userId);
        $data = array_merge($data, $notificationData);

        try {
            $enrollmentModel = new EnrollmentModel();
            $materialModel = new MaterialModel();
            $enrollments = $enrollmentModel->getUserEnrollments($userId);
            
            // Add material count for each course
            foreach ($enrollments as &$enrollment) {
                $materialCount = count($materialModel->getMaterialsByCourse($enrollment['course_id']));
                $enrollment['materials_count'] = $materialCount;
            }
            
            $data['enrollments'] = $enrollments;
        } catch (\Exception $e) {
            log_message('error', 'My Courses error: ' . $e->getMessage());
            $data['enrollments'] = [];
        }

        return view('student/my_courses', $data);
    }

    /**
     * View course materials for enrolled course
     */
    public function courseMaterials($courseId = null)
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        if (!$courseId) {
            $this->session->setFlashdata('error', 'Invalid course ID.');
            return redirect()->to('/student/my-courses');
        }

        $userId = (int) $this->session->get('userID');
        
        // Check if user is enrolled in this course
        $enrollmentModel = new EnrollmentModel();
        $enrollment = $enrollmentModel->where('user_id', $userId)
                                    ->where('course_id', $courseId)
                                    ->where('status', 'enrolled')
                                    ->first();

        if (!$enrollment) {
            $this->session->setFlashdata('error', 'You are not enrolled in this course.');
            return redirect()->to('/student/my-courses');
        }

        // Get course details
        $courseModel = new CourseModel();
        $course = $courseModel->find($courseId);
        
        if (!$course) {
            $this->session->setFlashdata('error', 'Course not found.');
            return redirect()->to('/student/my-courses');
        }

        // Get materials for this course
        $materialModel = new MaterialModel();
        $materials = $materialModel->getMaterialsByCourse($courseId);

        $data = [
            'user' => [
                'id' => $userId,
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'course' => $course,
            'materials' => $materials,
            'activeMenu' => 'my-courses'
        ];
        
        // Load notification data
        $notificationData = $this->loadNotificationData($userId);
        $data = array_merge($data, $notificationData);

        return view('student/course_materials', $data);
    }

    /**
     * Available Courses - Show courses not yet enrolled
     */
    public function availableCourses()
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        // Prevent caching to ensure fresh notification data
        $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');

        $userId = (int) $this->session->get('userID');
        $data = [
            'user' => [
                'id' => $userId,
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'activeMenu' => 'available-courses'
        ];
        
        // Load notification data
        $notificationData = $this->loadNotificationData($userId);
        $data = array_merge($data, $notificationData);

        try {
            $enrollmentModel = new EnrollmentModel();
            $enrollments = $enrollmentModel->getUserEnrollments($userId);
            
            // Get enrolled course IDs
            $enrolledCourseIds = array_column($enrollments, 'course_id');
            
            // Get all available courses not enrolled
            $courseBuilder = $this->db->table('courses');
            if (!empty($enrolledCourseIds)) {
                $courseBuilder->whereNotIn('id', $enrolledCourseIds);
            }
            $data['availableCourses'] = $courseBuilder->get()->getResultArray();
            
        } catch (\Exception $e) {
            log_message('error', 'Available Courses error: ' . $e->getMessage());
            $data['availableCourses'] = [];
        }

        return view('student/available_courses', $data);
    }

    /**
     * Assignments - Show student assignments
     */
    public function assignments()
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        $userId = (int) $this->session->get('userID');
        $data = [
            'user' => [
                'id' => $userId,
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'activeMenu' => 'assignments'
        ];
        
        // Load notification data
        $notificationData = $this->loadNotificationData($userId);
        $data = array_merge($data, $notificationData);

        // For now, we'll create a placeholder for assignments
        // You can implement assignment functionality later
        $data['assignments'] = [];

        return view('student/assignments', $data);
    }

    /**
     * Grades - Show student grades
     */
    public function grades()
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        $userId = (int) $this->session->get('userID');
        $data = [
            'user' => [
                'id' => $userId,
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'activeMenu' => 'grades'
        ];
        
        // Load notification data
        $notificationData = $this->loadNotificationData($userId);
        $data = array_merge($data, $notificationData);

        // For now, we'll create a placeholder for grades
        // You can implement grades functionality later
        $data['grades'] = [];

        return view('student/grades', $data);
    }

    /**
     * Announcements - Show all announcements
     */
    public function announcements()
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        $userId = (int) $this->session->get('userID');
        $data = [
            'user' => [
                'id' => $userId,
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'activeMenu' => 'announcements'
        ];
        
        // Load notification data
        $notificationData = $this->loadNotificationData($userId);
        $data = array_merge($data, $notificationData);

        try {
            $announcementModel = new AnnouncementModel();
            $data['announcements'] = $announcementModel->orderBy('created_at', 'DESC')->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Announcements error: ' . $e->getMessage());
            $data['announcements'] = [];
        }

        return view('student/announcements', $data);
    }

    /**
     * Notifications - Show student notifications
     */
    public function notifications()
    {
        $redirect = $this->checkAccess();
        if ($redirect) return $redirect;

        // Prevent caching to ensure fresh notification data
        $this->response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');

        $userId = (int) $this->session->get('userID');
        $data = [
            'user' => [
                'id' => $userId,
                'name' => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'activeMenu' => 'notifications'
        ];
        
        // Load notification data
        $notificationData = $this->loadNotificationData($userId);
        $data = array_merge($data, $notificationData);

        try {
            // Get all notifications for the user (for the main notifications page)
            $notificationModel = new NotificationModel();
            $data['all_notifications'] = $notificationModel->getAllNotificationsForUser($userId);
        } catch (\Exception $e) {
            log_message('error', 'Notifications error: ' . $e->getMessage());
            $data['all_notifications'] = [];
        }

        return view('student/notifications', $data);
    }

    /**
     * Mark notification as read (AJAX endpoint)
     */
    public function markAsRead()
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $request = $this->request;
        if (!$request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $notificationId = $request->getPost('notification_id');
        if (!$notificationId) {
            log_message('debug', 'Mark as read - No notification ID provided');
            return $this->response->setJSON(['success' => false, 'message' => 'Notification ID required']);
        }

        log_message('debug', 'Mark as read - Notification ID: ' . $notificationId);

        try {
            $notificationModel = new NotificationModel();
            
            // Check if notification exists and belongs to the user
            $notification = $notificationModel->where('id', $notificationId)
                                             ->where('user_id', $this->session->get('userID'))
                                             ->first();
            
            if (!$notification) {
                log_message('debug', 'Mark as read - Notification not found or does not belong to user: ' . $notificationId);
                return $this->response->setJSON(['success' => false, 'message' => 'Notification not found']);
            }
            
            $result = $notificationModel->markAsRead($notificationId);
            
            log_message('debug', 'Mark as read - Result: ' . ($result ? 'true' : 'false') . ' for notification ID: ' . $notificationId);
            
            if ($result) {
                return $this->response->setJSON(['success' => true, 'message' => 'Notification marked as read']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark notification as read']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Mark as read error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error']);
        }
    }

    /**
     * Mark all notifications as read (AJAX endpoint)
     */
    public function markAllAsRead()
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $request = $this->request;
        if (!$request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userId = (int) $this->session->get('userID');
        
        try {
            $notificationModel = new NotificationModel();
            $result = $notificationModel->where('user_id', $userId)
                                      ->where('is_read', 0)  // Only update unread notifications
                                      ->set('is_read', 1)
                                      ->update();
            
            log_message('debug', 'Mark all as read - Updated notifications for user: ' . $userId . ', result: ' . ($result ? 'true' : 'false'));
            
            if ($result !== false) {
                return $this->response->setJSON(['success' => true, 'message' => 'All notifications marked as read']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark notifications as read']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Mark all as read error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error']);
        }
    }

    /**
     * Test endpoint to check notification data
     */
    public function testNotifications()
    {
        $redirect = $this->checkAccess();
        if ($redirect) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $userId = (int) $this->session->get('userID');
        
        // Get fresh notification data
        $notificationModel = new NotificationModel();
        $notifications = $notificationModel->getNotificationsForUser($userId);
        $unreadCount = $notificationModel->getUnreadCount($userId);
        
        // Log for debugging
        log_message('debug', 'Test notifications endpoint - User: ' . $userId . ', Unread count: ' . $unreadCount . ', Unread notifications: ' . count($notifications));
        
        return $this->response->setJSON([
            'user_id' => $userId,
            'notification_count' => count($notifications),
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
