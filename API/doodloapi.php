<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

/**
 * Function to send email via SMTP without using external libraries
 */
function welcomeEmail($email, $name, $message, $codnum, $services) {
    $smtpHost = 'mail.doodlodesign.com'; // Replace with your SMTP server
    $smtpPort = 465; // Typically 587 for TLS or 465 for SSL
    $smtpUser = 'support@doodlodesign.com'; // Your SMTP username
    $smtpPass = 'YXgLE6m)v}6o'; // Your SMTP password

    $to = 'rushikesh@imposglobal.com';
    $subject = "$name DDS Website Lead";

    $emailBody = "
        <p><b>Name - </b> $name</p>
        <p><b>Email - </b> $email</p>
        <p><b>Phone - </b> $codnum</p>
        <p><b>Services - </b> $services</p>
        <p><b>Message - </b> $message</p>
    ";

    // SMTP commands
    $commands = [
        "HELO $smtpHost\r\n",
        "AUTH LOGIN\r\n",
        base64_encode($smtpUser) . "\r\n",
        base64_encode($smtpPass) . "\r\n",
        "MAIL FROM: <$smtpUser>\r\n",
        "RCPT TO: <$to>\r\n",
        "DATA\r\n",
        "Subject: $subject\r\n" .
        "To: $to\r\n" .
        "Content-Type: text/html; charset=UTF-8\r\n\r\n" .
        "$emailBody\r\n.\r\n",
        "QUIT\r\n"
    ];

    // Connect to SMTP server
    $socket = stream_socket_client("tcp://$smtpHost:$smtpPort", $errno, $errstr, 10);

    if (!$socket) {
        return "Failed to connect: $errstr ($errno)";
    }

    $response = '';

    foreach ($commands as $command) {
        fwrite($socket, $command);
        $response .= fgets($socket, 512);
    }

    fclose($socket);

    // Check for successful response
    if (strpos($response, '250 OK') !== false) {
        return true; // Email sent successfully
    } else {
        return "Failed to send email. Response: $response";
    }
}

// Example usage of the function
$response = [];
$data = json_decode(file_get_contents('php://input'), true);

if (is_array($data)) {
    $name = $data['name'];
    $email = $data['email'];
    $message = $data['message'];
    $code = $data['code'];
    $phone = $data['phone'];
    $codnum = $code . "-" . $phone;
    $services = isset($data['services']) ? json_encode($data['services']) : '';

    $emailResult = welcomeEmail($email, $name, $message, $codnum, $services);

    if ($emailResult === true) {
        $response['success'] = 'Email sent successfully';
    } else {
        $response['error'] = $emailResult;
    }
} else {
    $response['error'] = 'Invalid JSON input';
}

// Output the response as JSON
echo json_encode($response);
?>
