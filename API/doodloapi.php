<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

require '../assets/PHPMailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch Feedback Template using cURL
function getFeedbackTemplate() {
    $url = 'https://dds.doodlo.in/API/feedback.html';
    
    // Initialize cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as string
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if any
    
    // Execute the request
    $response = curl_exec($ch);
    
    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    }
    
    // Close the cURL session
    curl_close($ch);
    
    return $response;
}

// Send Feedback Email
function sendFeedbackEmail($email, $name, $message, $codnum, $services)
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

        // Fetch the template using cURL
        $feedbackTemplate = getFeedbackTemplate();

        // Replace placeholders in the feedback template (if any)
        $feedbackTemplate = str_replace('{{name}}', $name, $feedbackTemplate);

        // Set sender and recipient
        $mail->setFrom('support@doodlodesign.com', 'Doodlo Design Studio');
        $mail->addAddress($email, $name);  // Send to the user's email

        // Set email subject for feedback email
        $mail->Subject = 'Woohoo! Your Form Has Landed. We\'ll Be in Touch Soon!';

        // Set the feedback email body content (from the HTML template)
        $mail->isHTML(true);
        $mail->Body = $feedbackTemplate;
        $mail->AltBody = 'Please check your email client for the feedback request.';

        // Send the feedback email
        if (!$mail->send()) {
            //echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }

    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

// Notification Email
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
        $mail->setFrom('support@doodlodesign.com', 'Doodlo Design Studio');
        $mail->addAddress('hitesh@doodlodesigns.com', $name); // Replace with the correct recipient email
        $mail->addAddress('payal@doodlodesigns.com', $name); // Replace with the correct recipient email

        // Add Reply-To address
        $mail->addReplyTo('hitesh@doodlodesigns.com', 'Doodlo Design Studio');
    
        // Add CC address
        $mail->addCC('payal@doodlodesigns.com'); // Replace with the correct CC email

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
            //echo 'Message has been sent';
        }
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}

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
        sendFeedbackEmail($email, $name, $message, $codnum, $services);
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
