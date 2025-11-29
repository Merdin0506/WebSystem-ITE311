<?php
echo "Testing different route formats...\n";

$courseId = 1;
$routes = [
    "http://localhost/ITE311-harid/admin/course/$courseId/upload",
    "http://localhost/ITE311-harid/materials/upload/$courseId",
    "http://localhost/ITE311-harid/Materials/upload/$courseId"
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
    }
}
?>