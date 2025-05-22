<?php
include_once("config.php");

// Your JWT token
$jwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxMiwiZXhwIjoxNzQ3ODU4OTc0fQ.bSbmW5mgjNdqvpvcjqqhwQR_qr1RHqdI1wQENL1JbAM';

// Event data

// Load JSON from file
$jsonFile = 'examples/demo_event.json';
$jsonData = file_get_contents($jsonFile);
if ($jsonData === false) {
    die("Failed to read JSON file: $jsonFile");
}

// Initialize cURL
$ch = curl_init($apiBaseUrl . '/event');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

// Set headers and payload
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $jwt
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

// Execute request
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close connection
curl_close($ch);

// Handle response
if ($httpcode === 200 || $httpcode === 201) {
    echo "Event created successfully (HTTP $httpcode):\n$response";
} else {
    echo "Failed to create event (HTTP $httpcode):\n$response";
}