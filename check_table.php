<?php
$pdo = new PDO('mysql:host=localhost;dbname=lms_Harid', 'root', '');
$stmt = $pdo->query('DESCRIBE courses');
echo "Courses table structure:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}

echo "\nCourses data:\n";
$stmt = $pdo->query('SELECT * FROM courses LIMIT 3');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
?>