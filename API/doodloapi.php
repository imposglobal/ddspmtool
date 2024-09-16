<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

$response = [];

// Database connection parameters
$servername = "localhost";
$username = "ballapo7_pmtool";
$password = "pmtool@2024";
$dbname = "ballapo7_pmtool";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the raw input
$json = file_get_contents('php://input');

// Decode the JSON input into a PHP array
$data = json_decode($json, true);

// Check if data was received and process it
if (is_array($data)) {
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $message = $conn->real_escape_string($data['message']);
    $code = $conn->real_escape_string($data['code']);
    $phone = $conn->real_escape_string($data['phone']);

    // Check if services is set and encode it as JSON
    $services = isset($data['services']) ? json_encode($data['services']) : '';

    // Debugging: Log or output the services data
    error_log("Services: " . $services);

    // SQL to insert data
    $sql = "INSERT INTO contact_form (name, email, message, code, phone, services)
            VALUES ('$name', '$email', '$message', '$code', '$phone', '$services')";


    if ($conn->query($sql) === TRUE) {
        $response['success'] = 'Record added successfully';
    } else {
        $response['error'] = 'Error: ' . $conn->error;
    }
} else {
    $response['error'] = 'Invalid JSON input';
}

// Close connection
$conn->close();

// Output the response as JSON
echo json_encode($response);
?>
