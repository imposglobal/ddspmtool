<?php 

function welcomeEmail($email, $fname, $lname, $username, $password){
    $employeeName = $fname . " " . $lname;
    $to = $email;
    $subject = 'Welcome to Doodlo Designs Studio Project Management Tool!';
    $message = "
        <p>Dear $employeeName,</p>
        <p>Welcome to Doodlo Designs Studio Project Management Tool! We are thrilled to have you join us in our endeavor to streamline our project management processes and enhance collaboration within our team.</p>
        <p>Your account has been successfully created, and here are your login credentials:</p>
        <p>User ID: $username</p>
        <p>Password: $password</p>
        <p>Access Link: <a href='http://dds.doodlodesign.com/'>http://dds.doodlodesign.com/</a></p>
        <p>Please keep this information secure and do not share it with anyone. If you have any concerns regarding your account security or need assistance, feel free to reach out to our IT support team at <a href='mailto:rushikesh@imposglobal.com'>rushikesh@imposglobal.com</a>.</p>
        <p>With Doodlo Designs Studio Project Management Tool, you'll have access to a range of features designed to simplify project planning, task management, communication, and more. We believe this tool will greatly facilitate our workflow and help us achieve our project goals efficiently.</p>
        <p>To get started, simply log in using the provided credentials and explore the various functionalities available to you. We encourage you to familiarize yourself with the platform, and should you have any questions or require guidance, do not hesitate to contact our designated project management team or refer to the user guide provided.</p>
        <p>Thank you for being a part of the Doodlo Designs Studio team. We look forward to working together and achieving great success on our projects.</p>
        <p>Best regards,</p>
        <p>Doodlo Designs Studio</p>
    ";
    
    // Headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Doodlo Designs Studio <support@doodlodesign.com>" . "\r\n";
    $headers .= "Reply-To: Doodlo Designs Studio <support@doodlodesign.com>" . "\r\n"; // Set the reply-to address
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n"; // Add information about the mailer
    $headers .= "X-Priority: 1" . "\r\n"; // Set the priority of the email (1 is highest)
    $headers .= "X-MSMail-Priority: High" . "\r\n"; // Set the priority for Microsoft email clients
    $headers .= "Importance: High" . "\r\n"; // Set the importance level of the email

    // Sending email
    if (mail($to, $subject, $message, $headers)) {
        echo "Email has been sent";
    } else {
        echo "Message could not be sent.";
    }
}

//welcomeEmail('rollikuts@gmail.com', 'roll', 'test', 'test', 'test');
?>
