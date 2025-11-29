<?php
// Simple database check without CodeIgniter
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_Harid';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Database Connection Successful ===\n";
    
    // Check courses
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM courses');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Courses in database: " . $result['count'] . "\n";
    
    if ($result['count'] == 0) {
        echo "\nNo courses found. Creating sample courses...\n";
        
        $courses = [
            ['name' => 'Web Development Fundamentals', 'code' => 'WEB101', 'desc' => 'Introduction to HTML, CSS, and JavaScript'],
            ['name' => 'Database Management Systems', 'code' => 'DB201', 'desc' => 'Learn SQL and database design principles'],
            ['name' => 'Mobile Application Development', 'code' => 'MOB301', 'desc' => 'Build mobile apps with modern frameworks'],
            ['name' => 'Software Engineering', 'code' => 'SE401', 'desc' => 'Software development lifecycle and methodologies'],
            ['name' => 'Data Structures and Algorithms', 'code' => 'CS201', 'desc' => 'Core computer science concepts and problem solving']
        ];
        
        foreach ($courses as $course) {
            $sql = "INSERT INTO courses (course_name, course_code, description, created_at, updated_at) 
                    VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$course['name'], $course['code'], $course['desc']]);
            echo "✓ Created: {$course['name']} ({$course['code']})\n";
        }
        
        echo "\nSample courses created successfully!\n";
    } else {
        $stmt = $pdo->query('SELECT id, course_name, course_code FROM courses LIMIT 5');
        echo "\nExisting courses:\n";
        while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- ID: {$course['id']}, Name: {$course['course_name']}, Code: {$course['course_code']}\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
?>