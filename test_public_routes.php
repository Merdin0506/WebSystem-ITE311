<?php
echo "Testing corrected URLs with /public/ ...\n";

$courseId = 1;
$routes = [
    "http://localhost/ITE311-harid/public/materials/upload/$courseId",
    "http://localhost/ITE311-harid/public/admin/apiCourses"
];

foreach ($routes as $url) {
    echo "\nTesting: $url\n";
    $response = @file_get_contents($url);
    if ($response === false) {
        echo "✗ Failed\n";
    } else {
        echo "✓ Success - " . strlen($response) . " chars\n";
        if (strpos($response, 'Upload') !== false) {
            echo "  Contains 'Upload' text\n";
        }
        if (strpos($response, 'success') !== false) {
            echo "  Contains 'success' text\n";
        }
    }
}
?>