<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Web Development Fundamentals',
                'description' => 'Learn the basics of HTML, CSS, and JavaScript. Build responsive websites from scratch and understand modern web development practices.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'PHP Programming',
                'description' => 'Master PHP programming language for server-side development. Learn about variables, functions, classes, and database integration.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Database Management Systems',
                'description' => 'Comprehensive course on database design, SQL queries, normalization, and database administration using MySQL.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'JavaScript Advanced Concepts',
                'description' => 'Dive deep into JavaScript ES6+, asynchronous programming, promises, async/await, and modern JavaScript frameworks.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'React.js Development',
                'description' => 'Build modern user interfaces with React.js. Learn components, hooks, state management, and routing.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Node.js Backend Development',
                'description' => 'Create server-side applications with Node.js, Express.js, and work with APIs, authentication, and database integration.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Python Programming',
                'description' => 'Learn Python programming from basics to advanced topics including data structures, algorithms, and web frameworks.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Build cross-platform mobile applications using React Native or Flutter. Learn mobile UI/UX principles.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Data Structures and Algorithms',
                'description' => 'Master fundamental computer science concepts including arrays, linked lists, trees, graphs, sorting, and searching algorithms.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Software Engineering Principles',
                'description' => 'Learn software development methodologies, design patterns, testing, version control, and project management.',
                'instructor_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Using Query Builder
        $this->db->table('courses')->insertBatch($data);
    }
}