<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8"); // Set content type to JSON

$response = [];

if(isset($_POST['name'])){
  $name = $_POST['name'];
  // Add data to the response array
  $response['name'] = $name;
}

// Encode the response array as JSON and output it
echo json_encode($response);
?>
