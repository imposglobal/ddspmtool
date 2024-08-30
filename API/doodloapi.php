<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8"); // Set content type to JSON

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'])) {
        $response['name'] = $_POST['name'];
    }
    if (isset($_POST['email'])) {
        $response['email'] = $_POST['email'];
    }
    if (isset($_POST['message'])) {
        $response['message'] = $_POST['message'];
    }
    if (isset($_POST['code'])) {
        $response['code'] = $_POST['code'];
    }
    if (isset($_POST['phone'])) {
        $response['phone'] = $_POST['phone'];
    }
    // Assuming 'services' comes as a JSON-encoded string from the client side
    if (isset($_POST['services'])) {
        $response['services'] = json_decode($_POST['services'], true);
    }
} else {
    $response['error'] = 'Invalid request method';
}

// Output the response as JSON
echo json_encode($response);
?>
