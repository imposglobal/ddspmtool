<?php 
session_start();
require("db.php");

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize username and password inputs
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    // Prepare SQL statement to retrieve user from database
    $stmt = $db->prepare("SELECT * FROM employees WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password
        if ($password == $row['password']) {
            // Password is correct, start a new session
            session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['design'] = $row['designation'];
            $_SESSION['eid'] = $row['eid'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['fname']." ".$row['lname'];
            header('Location: ../Dashboard/');
            // Redirect to another page or display a success message
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }

    // Close statement
    $stmt->close();
}

