<?php 
require 'phpmailer/vendor/autoload.php'; // Path to Composer autoload.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function welcomeEmail($email,$fname,$lname,$username,$password){
    // Send email
$employeeName = $fname." ".$lname;
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'mail.imposglobal.com';  // Specify your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'rushikesh@imposglobal.com'; // SMTP username
    $mail->Password = 'Impos@2023'; // SMTP password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465; // TCP port to connect to

    //Recipients
    $mail->setFrom('your@example.com', 'Your Name');
    $mail->addAddress($email, $employeeName); // Add recipient

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Doodlo Designs Studio Project Management Tool!';
    $mail->Body    = "
        <p>Dear $employeeName,</p>
        <p>Welcome to Doodlo Designs Studio Project Management Tool! We are thrilled to have you join us in our endeavor to streamline our project management processes and enhance collaboration within our team.</p>
        <p>Your account has been successfully created, and here are your login credentials:</p>
        <p>User ID: $username</p>
        <p>Password: $password</p>
        <p>Please keep this information secure and do not share it with anyone. If you have any concerns regarding your account security or need assistance, feel free to reach out to our IT support team at <a href='mailto:rushikesh@imposglobal.com'>rushikesh@imposglobal.com</a>.</p>
        <p>With Doodlo Designs Studio Project Management Tool, you'll have access to a range of features designed to simplify project planning, task management, communication, and more. We believe this tool will greatly facilitate our workflow and help us achieve our project goals efficiently.</p>
        <p>To get started, simply log in using the provided credentials and explore the various functionalities available to you. We encourage you to familiarize yourself with the platform, and should you have any questions or require guidance, do not hesitate to contact our designated project management team or refer to the user guide provided.</p>
        <p>Thank you for being a part of the Doodlo Designs Studio team. We look forward to working together and achieving great success on our projects.</p>
        <p>Best regards,</p>
        <p>Doodlo Designs Studio</p>
    ";

    $mail->send();
    echo 'Email has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}