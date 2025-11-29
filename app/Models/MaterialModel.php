<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'course_id',
        'file_name', 
        'file_path',
        'created_at'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    /**
     * Insert a new material record
     */
    public function insertMaterial($data)
    {
        // Set created_at timestamp
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->insert($data);
    }

    /**
     * Get all materials for a specific course
     */
    public function getMaterialsByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get material by ID
     */
    public function getMaterialById($material_id)
    {
        return $this->find($material_id);
    }

    /**
     * Delete material by ID
     */
    public function deleteMaterial($material_id)
    {
        return $this->delete($material_id);
    }

    /**
     * Get materials for courses the user is enrolled in (or all materials for admins)
     */
    public function getMaterialsForEnrolledCourses($user_id, $user_role = 'student')
    {
        if ($user_role === 'admin') {
            // Admins can see all materials from all courses
            return $this->select('materials.*, courses.title as course_name, CONCAT("COURSE-", LPAD(courses.id, 3, "0")) as course_code')
                       ->join('courses', 'courses.id = materials.course_id')
                       ->orderBy('materials.created_at', 'DESC')
                       ->findAll();
        }
        
        // Students can only see materials from courses they're enrolled in
        return $this->select('materials.*, courses.title as course_name, CONCAT("COURSE-", LPAD(courses.id, 3, "0")) as course_code')
                   ->join('courses', 'courses.id = materials.course_id')
                   ->join('enrollments', 'enrollments.course_id = courses.id')
                   ->where('enrollments.user_id', $user_id)
                   ->where('enrollments.status', 'enrolled')
                   ->orderBy('materials.created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Check if user has access to material (enrolled in course)
     */
    public function userHasAccess($user_id, $material_id)
    {
        $material = $this->select('materials.course_id')
                        ->join('enrollments', 'enrollments.course_id = materials.course_id')
                        ->where('materials.id', $material_id)
                        ->where('enrollments.user_id', $user_id)
                        ->where('enrollments.status', 'enrolled')
                        ->first();
        
        return !empty($material);
    }
}