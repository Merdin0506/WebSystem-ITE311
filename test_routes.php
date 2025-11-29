<?php
// Test basic routing first
echo "Testing basic admin test endpoint...\n";
$response = @file_get_contents('http://localhost/ITE311-harid/admin/api/test');
if ($response === false) {
    echo "✗ Basic admin/api/test failed\n";
} else {
    echo "✓ Basic test response: $response\n";
}

echo "\nTesting courses endpoint...\n";
$response = @file_get_contents('http://localhost/ITE311-harid/admin/api/courses');
if ($response === false) {
    echo "✗ Courses endpoint failed\n";
} else {
    echo "✓ Courses response: $response\n";
}

echo "\nTesting with autoroute...\n";
$response = @file_get_contents('http://localhost/ITE311-harid/admin/apiCourses');
if ($response === false) {
    echo "✗ Auto route failed\n";
} else {
    echo "✓ Auto route response: $response\n";
}
?>