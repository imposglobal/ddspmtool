<?php 
require("db.php"); // Assuming db.php contains the database connection
require("mail.php"); // Assuming mail.php contains mailing functions


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ops'])) {
        $operation = $_POST['ops'];

        switch ($operation) {
            // Update Employee Profile
            case "update_profile":
                if(isset($_POST['eid'], $_POST['fname'], $_POST['lname'], $_POST['designation'], $_POST['email'], $_POST['phone'], $_POST['department'])) {
                    $eid = $_POST['eid'];
                    $fname = $_POST['fname'];
                    $lname = $_POST['lname'];
                    $designation = $_POST['designation'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    $department = $_POST['department'];  

                    $sql = "UPDATE employees SET fname = '$fname', lname = '$lname', designation = '$designation', email = '$email', phone = '$phone', department = '$department' WHERE eid = '$eid'";
                    if ($db->query($sql) === TRUE) 
                    {
                        // Return success message as JSON
                        echo json_encode(array("success" => true, "message" => "Profile updated successfully"));
                    } else {
                        // If an error occurred, send error response
                        echo json_encode(array("success" => false, "message" => "Error updating profile: " . $db->error));
                    }
                } else {
                    echo "Incomplete data for updating profile";
                }
                break;

            // Update Employee Password
            case "update_password":
                if(isset($_POST['eid'], $_POST['newpassword'], $_POST['renewpassword'])) {
                    $eid = $_POST['eid'];
                    $newPassword = $_POST['newpassword'];
                    $renewPassword = $_POST['renewpassword'];

                    // Validate passwords
                    if ($newPassword != $renewPassword) {
                        echo '<div class="alert alert-danger">Passwords do not match!</div>';
                        exit;
                    }

                    $sql = "UPDATE employees SET password = '$newPassword' WHERE eid = '$eid'";
                    if ($db->query($sql) === TRUE) {
                        // Return success message as JSON
                        echo json_encode(array("success" => true, "message" => "Password updated successfully"));
                    } else {
                        // If an error occurred, send error response
                        echo json_encode(array("success" => false, "message" => "Error updating password: " . $db->error));
                    }
                } else {
                    echo "Incomplete data for updating password";
                }
                break;



                 // Update Task
                 case "update_task":
                    if(isset($_POST['tid'], $_POST['sdate'], $_POST['edate'], $_POST['status'], $_POST['task_type'], $_POST['title'], $_POST['time_frame'], $_POST['editor1'])) {
                        $tid = $_POST['tid'];
                        $sdate = $_POST['sdate'];
                        $edate = $_POST['edate'];
                        $task_type = $_POST['task_type'];
                        $title = $_POST['title'];
                        $status = $_POST['status'];
                        $time_frame = $_POST['time_frame'];
                        $editor1 = $_POST['editor1'];
    
                        $sql = "UPDATE task SET start_date = '$sdate', end_date = '$edate', task_type = '$task_type', title = '$title', status = '$status', timeframe = '$time_frame', description = '$editor1' WHERE tid = '$tid'";
                        if ($db->query($sql) === TRUE) 
                        {
                            // Return success message as JSON
                            echo json_encode(array("success" => true, "message" => "task updated successfully"));
                        } else {
                            // If an error occurred, send error response
                            echo json_encode(array("success" => false, "message" => "Error updating profile: " . $db->error));
                        }
                    } else {
                        echo "Incomplete data for updating task";
                    }
                    break;

            default:
                echo "Invalid operation";
        }
    } else {
        echo "Operation not specified";
    }
} else {
    echo "Invalid request method";
}
?>
