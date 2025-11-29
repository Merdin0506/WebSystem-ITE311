<?php

namespace App\Controllers;

class Debug extends BaseController
{
    public function testCourses()
    {
        // Simple test without authentication
        $db = \Config\Database::connect();
        
        // Get courses with instructor names
        $courses = $db->table('courses')
            ->select('courses.*, users.name as instructor_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->orderBy('courses.created_at', 'DESC')
            ->get()
            ->getResultArray();
            
        return view('courses/index', [
            'courses' => $courses,
            'enrolledCourseIds' => [],
            'searchTerm' => ''
        ]);
    }
    
    public function testAuth()
    {
        $session = session();
        return $this->response->setJSON([
            'isLoggedIn' => $session->get('isLoggedIn'),
            'userID' => $session->get('userID'),
            'name' => $session->get('name'),
            'role' => $session->get('role'),
            'email' => $session->get('email')
        ]);
    }
    
    public function testSearch()
    {
        $db = \Config\Database::connect();
        $searchTerm = $this->request->getVar('search') ?? 'Web';
        
        $courses = $db->table('courses')
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
            
        return $this->response->setJSON([
            'search_term' => $searchTerm,
            'courses_found' => count($courses),
            'courses' => $courses
        ]);
    }
}