<?php
echo "Testing autoroute format...\n";
$response = @file_get_contents('http://localhost/ITE311-harid/admin/apiCourses');
if ($response === false) {
    echo "✗ Auto route failed\n";
} else {
    echo "✓ Auto route response: $response\n";
}

echo "\nTesting test method...\n";
$response = @file_get_contents('http://localhost/ITE311-harid/admin/test');
if ($response === false) {
    echo "✗ Test method failed\n";
} else {
    echo "✓ Test method response: $response\n";
}
?>