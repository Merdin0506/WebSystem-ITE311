<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Materials extends BaseController
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        
        // Create uploads directory if it doesn't exist
        $uploadPath = FCPATH . 'uploads/materials';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
    }

    /**
     * Display upload form and handle file upload
     */
    public function upload($course_id = null)
    {
        // Check if user is admin/instructor
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Access denied.');
        }

        if ($course_id === null) {
            return redirect()->back()->with('error', 'Invalid course ID.');
        }

        // Get course details
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        // Handle POST request (file upload)
        if ($this->request->getMethod() === 'POST') {
            return $this->processUpload($course_id);
        }

        // Display upload form
        $data = [
            'title' => 'Upload Material - ' . $course['title'],
            'course' => $course,
            'course_id' => $course_id
        ];

        return view('admin/upload_material', $data);
    }

    /**
     * Process file upload
     */
    private function processUpload($course_id)
    {
        $isAjax = $this->request->isAJAX();
        
        $validationRule = [
            'material' => [
                'label' => 'Material File',
                'rules' => [
                    'uploaded[material]',
                    'max_size[material,10240]', // 10MB max
                    'ext_in[material,pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png]'
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            $errors = $this->validator->getErrors();
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', $errors)
                ]);
            }
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $errors);
        }

        $file = $this->request->getFile('material');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate unique filename
            $originalName = $file->getName();
            $newName = time() . '_' . $originalName;
            
            // Move file to uploads directory
            $uploadPath = FCPATH . 'uploads/materials';
            $file->move($uploadPath, $newName);

            // Save to database
            $materialData = [
                'course_id' => $course_id,
                'file_name' => $originalName,
                'file_path' => 'uploads/materials/' . $newName
            ];

            if ($this->materialModel->insertMaterial($materialData)) {
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Material uploaded successfully!'
                    ]);
                }
                return redirect()->to('/admin/course/' . $course_id . '/materials')
                               ->with('success', 'Material uploaded successfully!');
            } else {
                // Delete uploaded file if database insert failed
                unlink($uploadPath . '/' . $newName);
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to save material to database.'
                    ]);
                }
                return redirect()->back()->with('error', 'Failed to save material to database.');
            }
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File upload failed.'
            ]);
        }
        return redirect()->back()->with('error', 'File upload failed.');
    }

    /**
     * Delete material
     */
    public function delete($material_id)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied.']);
        }

        $material = $this->materialModel->find($material_id);
        if (!$material) {
            return $this->response->setJSON(['success' => false, 'message' => 'Material not found.']);
        }

        // Delete file from server
        $filePath = FCPATH . $material['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete from database
        if ($this->materialModel->deleteMaterial($material_id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Material deleted successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete material.']);
    }

    /**
     * Download material (for enrolled students)
     */
    public function download($material_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Please log in to download materials.');
        }

        $user_id = session()->get('userId');
        $user_role = session()->get('role');

        // Get material details
        $material = $this->materialModel->find($material_id);
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // Check access permissions
        if ($user_role !== 'admin') {
            // Students must be enrolled in the course
            if (!$this->materialModel->userHasAccess($user_id, $material_id)) {
                return redirect()->back()->with('error', 'Access denied. You must be enrolled in this course.');
            }
        }
        // Admins have access to all materials without enrollment requirement

        // Check if file exists
        $filePath = FCPATH . $material['file_path'];
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        // Force download
        return $this->response->download($filePath, null, true);
    }

    /**
     * List materials for a course (admin view)
     */
    public function listByCourse($course_id)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Access denied.');
        }

        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        $data = [
            'title' => 'Course Materials - ' . $course['title'],
            'course' => $course,
            'materials' => $materials
        ];

        return view('admin/course_materials', $data);
    }

    /**
     * List materials for enrolled students
     */
    public function studentMaterials()
    {
        // Check if user is logged in as student
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user_id = session()->get('userId');
        $user_role = session()->get('role');
        $materials = $this->materialModel->getMaterialsForEnrolledCourses($user_id, $user_role);

        $data = [
            'title' => 'My Course Materials',
            'materials' => $materials
        ];

        return view('student/materials', $data);
    }

    /**
     * Quick upload from admin dashboard
     */
    public function processQuickUpload()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            echo '<script>alert("Access denied. Please login as admin."); window.close();</script>';
            return;
        }

        $course_id = $this->request->getPost('course_id');
        if (!$course_id) {
            echo '<script>alert("No course selected."); window.close();</script>';
            return;
        }

        // Process the upload
        $result = $this->processUpload($course_id);
        
        // Since this opens in a new tab, show a simple result page
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Upload Result</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light p-4">
            <div class="container">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-success"><i class="fas fa-check-circle me-2"></i>Upload Complete!</h4>
                        <p class="text-muted">Material has been uploaded successfully.</p>
                        <div class="mt-3">
                            <button onclick="window.close()" class="btn btn-secondary">Close Window</button>
                            <a href="/ITE311-harid/public/admin/course/' . $course_id . '/materials" class="btn btn-primary" target="_parent">View Materials</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }
}