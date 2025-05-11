<?php
include_once("config.php");

// Build query string from current page's URL parameters
$queryString = http_build_query($_GET);

// Full API URL with parameters
$apiUrl = $apiBaseUrl . '/query?' . $queryString;


$ch = curl_init($apiUrl);

// Return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Follow redirects if needed
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

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