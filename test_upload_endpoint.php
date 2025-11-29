<?php
echo "Testing upload endpoint accessibility...\n";

// Test if we can reach the upload endpoint
$courseId = 1; // Using course ID 1 which we know exists
$testUrl = "http://localhost/ITE311-harid/admin/course/$courseId/upload";

echo "Testing URL: $testUrl\n";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'User-Agent: Test Script'
        ]
    ]
]);

$response = @file_get_contents($testUrl, false, $context);
if ($response === false) {
    echo "✗ Cannot reach upload endpoint\n";
    $error = error_get_last();
    echo "Error: " . $error['message'] . "\n";
} else {
    echo "✓ Upload endpoint is reachable\n";
    echo "Response length: " . strlen($response) . " characters\n";
    
    if (strpos($response, 'Upload Material') !== false) {
        echo "✓ Upload form appears to be working\n";
    } else {
        echo "✗ Upload form not found in response\n";
    }
}
?>