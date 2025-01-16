<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");


// function welcomeEmail($email, $name, $message,  $codnum, $services){
//     $employeeName = $name;
//     $to = 'rushikesh@imposglobal.com';
//     $subject = $name . ' DDS Website Lead';
//     $message = "
//         <p><b>Name - </b> $employeeName</p>
//         <p><b>Email - </b> $email</p>
//         <p><b>Phone - </b> $codnum</p>
//         <p><b>Services - </b> $services</p>
//         <p><b>Message - </b> $message</p>
//     ";
    
//     // Headers
//     $headers = "MIME-Version: 1.0" . "\r\n";
//     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//     $headers .= "From: Doodlo Designs Studio <support@doodlodesign.com>" . "\r\n";
//     $headers .= "Reply-To: Doodlo Designs Studio <support@doodlodesign.com>" . "\r\n"; // Set the reply-to address
//     $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n"; // Add information about the mailer
//     $headers .= "X-Priority: 1" . "\r\n"; // Set the priority of the email (1 is highest)
//     $headers .= "X-MSMail-Priority: High" . "\r\n"; // Set the priority for Microsoft email clients
//     $headers .= "Importance: High" . "\r\n"; // Set the importance level of the email

//     // Sending email
//     if (mail($to, $subject, $message, $headers)) {
     
//     } else {
      
//     }
// }



// with smtp configuration

require '../assets/PHPMailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function welcomeEmail($email, $name, $message, $codnum, $services)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'mail.doodlodesign.com';  // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'support@doodlodesign.com'; // SMTP username
        $mail->Password = 'doodlo@2025'; // SMTP password  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Use 587 for STARTTLS
        $mail->Timeout = 30;

        // Set sender and recipient
        $mail->setFrom('support@doodlodesign.com', 'Rushikesh');
        $mail->addAddress('rushikesh@imposglobal.com', $name); // Replace with the correct recipient email

        // Set email subject
        $mail->Subject = $name . ' DDS Website Lead';

        // Set email body content (correct variable embedding)
        $mail->isHTML(true);
        $mail->Body = '<p><b>Name - </b>' . $name . '</p>
                       <p><b>Email - </b>' . $email . '</p>
                       <p><b>Phone - </b>' . $codnum . '</p>
                       <p><b>Services - </b>' . $services . '</p>
                       <p><b>Message - </b>' . $message . '</p>';
        $mail->AltBody = 'Name: ' . $name . '\nEmail: ' . $email . '\nPhone: ' . $codnum . '\nServices: ' . $services . '\nMessage: ' . $message;

        // Send email
        if ($mail->send()) {
            echo 'Message has been sent';
        }
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Test the function with sample data
// welcomeEmail('rollikuts@gmail.com', 'Roll', 'Test', 'testuser', 'testpassword');

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
    $codnum = $code."-".$phone;
    // Check if services is set and encode it as JSON
    $services = isset($data['services']) ? json_encode($data['services']) : '';

    // Debugging: Log or output the services data
    error_log("Services: " . $services);

    // SQL to insert data
    $sql = "INSERT INTO contact_form (name, email, message, code, phone, services)
            VALUES ('$name', '$email', '$message', '$code', '$phone', '$services')";
   

    if ($conn->query($sql) === TRUE) {
        $response['success'] = 'Record added successfully';
        welcomeEmail($email, $name, $message,  $codnum, $services);
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
          
            $response = createContact($accessToken, $contactData);
           
           
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
