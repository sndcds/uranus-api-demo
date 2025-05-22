<?php
header("Access-Control-Allow-Origin: http://localhost:8000"); // match frontend
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Read POST body
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['token'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing token"]);
    exit;
}

$token = $data['token'];

// Set cookie (dev-friendly version)
setcookie("auth_token", $token, [
    'expires' => time() + 3600,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax' // Change to 'None' + 'secure' for production
]);

echo json_encode(["status" => "cookie set"]);
?>