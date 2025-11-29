<?php
echo "Testing basic web server access...\n";

$baseUrls = [
    "http://localhost/ITE311-harid/",
    "http://localhost/ITE311-harid/public/",
    "http://localhost/ITE311-harid/public/index.php"
];

foreach ($baseUrls as $url) {
    echo "\nTesting: $url\n";
    $response = @file_get_contents($url);
    if ($response === false) {
        echo "✗ Failed\n";
    } else {
        echo "✓ Success - " . strlen($response) . " chars\n";
        if (strpos($response, 'CodeIgniter') !== false) {
            echo "  Contains CodeIgniter\n";
        }
        if (strpos($response, 'Portal') !== false) {
            echo "  Contains Portal\n";
        }
    }
}
?>