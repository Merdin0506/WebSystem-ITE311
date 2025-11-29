<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = new \Config\Database();
$db = \Config\Database::connect();

echo "=== Checking Courses Database ===\n";

try {
    $query = $db->query('SELECT COUNT(*) as count FROM courses');
    $result = $query->getRow();
    echo "Courses in database: " . $result->count . "\n";
    
    if ($result->count > 0) {
        $courses = $db->query('SELECT id, course_name, course_code FROM courses LIMIT 10');
        echo "\nExisting courses:\n";
        foreach ($courses->getResultArray() as $course) {
            echo "- ID: {$course['id']}, Name: {$course['course_name']}, Code: {$course['course_code']}\n";
        }
    } else {
        echo "\nNo courses found. Let's create some sample courses:\n";
        
        // Create sample courses
        $sampleCourses = [
            ['course_name' => 'Web Development Fundamentals', 'course_code' => 'WEB101', 'description' => 'Introduction to HTML, CSS, and JavaScript'],
            ['course_name' => 'Database Management Systems', 'course_code' => 'DB201', 'description' => 'Learn SQL and database design principles'],
            ['course_name' => 'Mobile Application Development', 'course_code' => 'MOB301', 'description' => 'Build mobile apps with modern frameworks'],
            ['course_name' => 'Software Engineering', 'course_code' => 'SE401', 'description' => 'Software development lifecycle and methodologies'],
            ['course_name' => 'Data Structures and Algorithms', 'course_code' => 'CS201', 'description' => 'Core computer science concepts and problem solving']
        ];
        
        foreach ($sampleCourses as $course) {
            $course['created_at'] = date('Y-m-d H:i:s');
            $course['updated_at'] = date('Y-m-d H:i:s');
            
            $db->table('courses')->insert($course);
            echo "✓ Created course: {$course['course_name']} ({$course['course_code']})\n";
        }
        
        echo "\nSample courses created successfully!\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
?>