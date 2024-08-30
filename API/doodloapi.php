<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8"); // Set content type to JSON

$response = [];

// Read the raw input
$json = file_get_contents('php://input');

// Decode the JSON input into a PHP array
$data = json_decode($json, true);

// Check if data was received and process it
if (is_array($data)) {
    if (isset($data['name'])) {
        $response['name'] = $data['name'];
    }
    if (isset($data['email'])) {
        $response['email'] = $data['email'];
    }
    if (isset($data['message'])) {
        $response['message'] = $data['message'];
    }
    if (isset($data['code'])) {
        $response['code'] = $data['code'];
    }
    if (isset($data['phone'])) {
        $response['phone'] = $data['phone'];
    }
    if (isset($data['services'])) {
        $response['services'] = $data['services'];
    }
} else {
    $response['error'] = 'Invalid JSON input';
}

// Output the response as JSON
echo json_encode($response);
?>
