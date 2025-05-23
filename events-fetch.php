<?php
include_once("config.php");

$post = $_SERVER['REQUEST_METHOD'] === 'POST';

if ($post) {
    $apiUrl = $apiBaseUrl . '/query?mode=event'; // Don't append query string to URL
    $postData = http_build_query($_POST); // or manually build your key-value pairs
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true); // This makes it a POST request
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Send POST data
}
else {
    $queryString = http_build_query($_GET);
    $apiUrl = $apiBaseUrl . '/query?mode=event&' . $queryString;
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
}


// Execute request
$response = curl_exec($ch);

// Get HTTP status code
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close connection
curl_close($ch);

// Check response and status
if ($httpCode !== 200 || $response === false) {
    echo "<p>⚠️ Error fetching data from API: $apiUrl</p>";
    echo "<p>HTTP status code: $httpCode</p>";
    exit;
}
else {
    // Decode JSON
    $data = json_decode($response, true);
    if (!$data || !isset($data['events'])) {
        echo "<p>No Events</p>";
        exit;
    }
}

?>