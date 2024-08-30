<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Initialize an empty response array
$response = [];

// Read the raw input
$json = file_get_contents('php://input');

// Decode the JSON input into a PHP array
$data = json_decode($json, true);

// Check if data was received and process it
if (is_array($data)) {
    // Assign received data to response array
    $response = [
        'name' => $data['name'] ?? '',
        'email' => $data['email'] ?? '',
        'message' => $data['message'] ?? '',
        'code' => $data['code'] ?? '',
        'phone' => $data['phone'] ?? '',
        'services' => $data['services'] ?? []
    ];
} else {
    $response['error'] = 'Invalid JSON input';
}

// Output the response as JSON
echo json_encode($response);
?>
