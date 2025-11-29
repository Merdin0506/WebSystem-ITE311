<?php
// Test the API endpoint
$url = 'http://localhost/ITE311-harid/admin/api/courses';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'X-Requested-With: XMLHttpRequest',
            'Content-Type: application/json'
        ]
    ]
]);

echo "Testing API endpoint: $url\n";

$response = file_get_contents($url, false, $context);
if ($response === false) {
    echo "Error: Could not fetch data from API\n";
} else {
    echo "Response:\n";
    echo $response . "\n";
    
    $data = json_decode($response, true);
    if ($data && isset($data['success'])) {
        if ($data['success']) {
            echo "\n✓ API working! Found " . count($data['courses']) . " courses\n";
        } else {
            echo "\n✗ API error: " . $data['message'] . "\n";
        }
    }
}
?>