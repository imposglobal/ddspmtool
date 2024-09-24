<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Email Noti
function welcomeEmail($email, $name, $fname, $codnum, $message, $services) {
    $emailContent = "
    <html>
    <head>
        <title>Email Form Submission</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 10px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <h2>Form Submission Details</h2>
        <table>
            <tr>
                <th>Name</th>
                <td>{$fname}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{$email}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{$codnum}</td>
            </tr>
            <tr>
                <th>Services</th>
                <td>{$services}</td>
            </tr>
            <tr>
                <th>Message</th>
                <td>{$message}</td>
            </tr>
        </table>
    </body>
    </html>
    ";

    // Headers for the email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Doodlo Designs Studio <support@doodlodesign.com>" . "\r\n";
    $headers .= "Reply-To: Doodlo Designs Studio <support@doodlodesign.com>" . "\r\n"; 
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n"; 
    $headers .= "X-Priority: 1" . "\r\n"; 
    $headers .= "X-MSMail-Priority: High" . "\r\n"; 
    $headers .= "Importance: High" . "\r\n";

    // Send email
    mail($email, "Form Submission Details", $emailContent, $headers);
    // if (mail($email, "Form Submission Details", $emailContent, $headers)) {
    //     return "Email sent successfully!";
    // } else {
    //     return "Failed to send email!";
    // }
}




// function start
/**
 * Function to get the OAuth access token
 *
 * @return string The access token or an error message
 */
function getAccessToken() {
    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, 'https://marketing.k99bs.com/oauth/v2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => '4_kszm8z5f3eo00s84wogkcs8c4gggooow00gsckc8kc04wsggg',
        'client_secret' => '513t8hjf4nk8gc8wk0sko0kswwwoowkkkcsggsk8okgos8o000'
    ]));

    // Set headers
    $headers = [
        'Content-Type: application/x-www-form-urlencoded'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL request
    $result = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error = 'Error: ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Decode JSON response
    $responseData = json_decode($result, true);

    // Close cURL session
    curl_close($ch);

    // Check if decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
        // Return access token
        return $responseData['access_token'] ?? 'No access token found';
    } else {
        return 'JSON decode error: ' . json_last_error_msg();
    }
}

/**
 * Function to create a contact in Mautic with tags
 *
 * @param string $accessToken The OAuth access token
 * @param array $contactData The contact data to be sent
 * @return string The response from Mautic or an error message
 */
function createContact($accessToken, $contactData) {
    // Initialize cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, 'https://marketing.k99bs.com/api/contacts/new');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contactData));
    
    // Set headers
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL request
    $result = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error = 'Error: ' . curl_error($ch);
        curl_close($ch);
        return $error;
    }

    // Close cURL session
    curl_close($ch);

    // Return response
    return $result;
}
// function End

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
        // Get the access token
        $accessToken = getAccessToken();

        // Check if the access token was retrieved successfully
        if (strpos($accessToken, 'Error:') === false && strpos($accessToken, 'JSON decode error:') === false) {
            // Define the contact data including tags
            $contactData = [
                'firstname' => $name,
                'lastname' => ' ',
                'email' => $email,
                'phone' => $phone,
                'tags' => ['Doodlo'] // Tags to be added
            ];

            // Create the contact
            $codnum = $code.$phone;
            $response = createContact($accessToken, $contactData);
            welcomeEmail($email, $name, , $codnum, $message , $services);

           
} else {
    // Output the error
    echo $accessToken;
}
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
