<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;

class Auth extends BaseController
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }
    
    /**
     * Display registration form and process registration
     */
    public function register()
    {
        // Check if form was submitted (POST request)
        if ($this->request->getMethod() === 'POST') {
            // Set validation rules
            $rules = [
                'name' => [
                    'rules' => 'required|min_length[3]|max_length[50]',
                    'errors' => [
                        'required' => 'Name is required',
                        'min_length' => 'Name must be at least 3 characters',
                        'max_length' => 'Name cannot exceed 50 characters'
                    ]
                ],
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please enter a valid email address'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[6]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 6 characters'
                    ]
                ],
                'password_confirm' => [
                    'rules' => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Password confirmation is required',
                        'matches' => 'Password confirmation does not match'
                    ]
                ]
            ];
            
            // Validate the form data
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                
                // Check if email already exists
                $existingUser = $this->db->table('users')->where('email', $email)->get()->getRow();
                
                if ($existingUser) {
                    session()->setFlashdata('error', 'This email is already registered.');
                } else {
                    // Hash the password
                    $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                    
                    // Prepare user data
                    $userData = [
                        'name' => $this->request->getPost('name'),
                        'email' => $email,
                        'password' => $hashedPassword,
                        'role' => 'user', // Default role
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Save user to database
                    if ($this->db->table('users')->insert($userData)) {
                        // Set success flash message
                        session()->setFlashdata('success', 'Registration successful! Please login to continue.');
                        return redirect()->to('/auth/login');
                    } else {
                        // Set error flash message
                        session()->setFlashdata('error', 'Registration failed. Please try again.');
                    }
                }
            } else {
                // Validation failed, errors will be displayed in the view
                $data['validation'] = $this->validator;
            }
        }
        
        // Load registration view
        return view('auth/register', $data ?? []);
    }
    
    /**
     * Display login form and process login
     */
    public function login()
    {
        // Check if form was submitted (POST request)
        if ($this->request->getMethod() === 'POST') {
            // Set validation rules
            $rules = [
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please enter a valid email address'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required'
                    ]
                ]
            ];
            
            // Validate the form data
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                
                // Check database for user with provided email
                $user = $this->db->table('users')->where('email', $email)->get()->getRowArray();
                
                if ($user && password_verify($password, $user['password'])) {
                    // Credentials are correct, create user session
                    $sessionData = [
                        'userID' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true
                    ];
                    
                    session()->set($sessionData);
                    
                    // Set welcome flash message
                    session()->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');

                } else {
                    // Invalid credentials
                    session()->setFlashdata('error', 'Invalid email or password.');
                }
            } else {
                // Validation failed
                $data['validation'] = $this->validator;
            }
        }
        
        // Load login view
        return view('auth/login', $data ?? []);
    }
    
    /**
     * Destroy user session and logout
     */
    public function logout()
    {
        // Destroy the current session
        session()->destroy();
        
        // Set logout message
        session()->setFlashdata('success', 'You have been logged out successfully.');
        
        // Redirect to login page
        return redirect()->to('/auth/login');
    }
    
    /**
     * Protected dashboard page for logged-in users only
     */
    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('/auth/login');
        }
        
        // Get user data from session
        $userId = (int) session()->get('userID');
        $data = [
            'user' => [
                'id' => $userId,
                'name' => session()->get('name'),
                'email' => session()->get('email'),
                'role' => session()->get('role')
            ]
        ];

        // Load user enrollments
        try {
            $enrollmentModel = new EnrollmentModel();
            $enrollments = $enrollmentModel->getUserEnrollments($userId);
            $data['enrollments'] = $enrollments;

            // Determine available courses (not enrolled yet)
            $enrolledCourseIds = array_column($enrollments, 'course_id');
            $courseBuilder = $this->db->table('courses');
            if (!empty($enrolledCourseIds)) {
                $courseBuilder->whereNotIn('id', $enrolledCourseIds);
            }
            $data['availableCourses'] = $courseBuilder->get()->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());
            $data['enrollments'] = [];
            $data['availableCourses'] = [];
        }
        
        // Load dashboard view
        return view('auth/dashboard', $data);
    }
}
