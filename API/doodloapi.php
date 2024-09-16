<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// get accesss token
function getAccessToken($mauticBaseUrl, $clientId, $clientSecret) {
    // OAuth2 token endpoint
    $tokenUrl = $mauticBaseUrl . '/oauth/v2/token';

    // Prepare POST fields
    $postFields = http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
    ]);

    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
    ]);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    // Decode the response to get the access token
    $responseData = json_decode($response, true);
    curl_close($ch);

    // Return the access token if available
    return $responseData['access_token'] ?? null;
}

// Usage example
$mauticBaseUrl = 'https://marketing.k99bs.com; // Replace with your Mautic base URL
$clientId = '4_kszm8z5f3eo00s84wogkcs8c4gggooow00gsckc8kc04wsggg'; // Replace with your client ID
$clientSecret = '513t8hjf4nk8gc8wk0sko0kswwwoowkkkcsggsk8okgos8o000'; // Replace with your client secret

$accessToken = getAccessToken($mauticBaseUrl, $clientId, $clientSecret);

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

    // mautic integration start
    // Mautic API details
    $mauticBaseUrl = 'https://marketing.k99bs.com'; // Replace with your Mautic base URL
    $accessToken = $accessToken; // Replace with your OAuth access token
    
    // Contact data
    $contactData = [
        'firstname' => $name,
        'lastname' => ' ',
        'email' => '$email,
        'tags' => [ // Tags to associate with the contact
            'Doodlo' // Make sure this matches the tag created
        ],
    ];
    
    // Initialize cURL
    $ch = curl_init();
    
    // Set cURL options to create a contact
    curl_setopt($ch, CURLOPT_URL, $mauticBaseUrl . '/api/contacts/new');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contactData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ]);
    
    // Execute cURL request
    $response = curl_exec($ch);
    
    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        // Decode and print the response
        $responseData = json_decode($response, true);
        print_r($responseData);
    }
    
    // Close cURL
    curl_close($ch);
    // Mautic end

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
