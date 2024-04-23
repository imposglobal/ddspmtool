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
                    if(isset($_POST['tid'], $_POST['sdate'], $_POST['edate'], $_POST['status'], $_POST['task_type'], $_POST['title'], $_POST['time_frame'], $_POST['priority'], $_POST['editor1'])) {
                        $tid = $_POST['tid'];
                        $sdate = $_POST['sdate'];
                        $edate = $_POST['edate'];
                        $task_type = $_POST['task_type'];
                        $title = $_POST['title'];
                        $status = $_POST['status'];
                        $time_frame = $_POST['time_frame'];
                        $priority = $_POST['priority'];
                        $editor1 = $_POST['editor1'];
    
                        $sql = "UPDATE task SET start_date = '$sdate', end_date = '$edate', task_type = '$task_type', title = '$title', status = '$status', timeframe = '$time_frame', priority = '$priority', description = '$editor1' WHERE tid = '$tid'";
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
              
                       // Update Project
                 case "update_project":
                    if(isset($_POST['pid'], $_POST['project_name'], $_POST['status'], $_POST['editor1'])) {
                        $pid = $_POST['pid'];
                        $project_name = $_POST['project_name'];
                        $status = $_POST['status'];
                        $editor1 = $_POST['editor1'];
    
                        $sql = "UPDATE projects SET project_name = '$project_name', description = '$editor1', status = '$status' WHERE pid = '$pid'";
                        if ($db->query($sql) === TRUE) 
                        {
                            // Return success message as JSON
                            echo json_encode(array("success" => true, "message" => "project updated successfully"));
                        } else {
                            // If an error occurred, send error response
                            echo json_encode(array("success" => false, "message" => "Error updating project: " . $db->error));
                        }
                    } else {
                        echo "Incomplete data for updating project";
                    }
                    break;


                      // Update Manager Status
                 case "update_mstatus":
                    if(isset($_POST['tid'], $_POST['m_status']))
                     {
                        $tid = $_POST['tid'];
                        $m_status= $_POST['m_status'];   
                        $sql = "UPDATE task SET m_status = '$m_status' WHERE tid = '$tid'";
                        if ($db->query($sql) === TRUE) 
                        {
                            // Return success message as JSON
                            echo json_encode(array("success" => true, "message" => "status updated successfully"));
                        } else {
                            // If an error occurred, send error response
                            echo json_encode(array("success" => false, "message" => "Error updating project: " . $db->error));
                        }
                    } else {
                        echo "Incomplete data for updating status";
                    }
                    break;


                    // add task timings in the database

                    // case "start_task_time":
                    //     if(isset($_POST['tid'], $_POST['eid'], $_POST['pid'])) {
                    //         date_default_timezone_set('Asia/Kolkata');
                    //         $tid = $_POST['tid'];
                    //         $eid = $_POST['eid'];
                    //         $pid = $_POST['pid'];
                    //         $timestamp = date('h:i:s A');
                    //         $check_sql = "SELECT * FROM `task_time` WHERE `tid`='$tid' AND `eid`='$eid' AND `pid`='$pid'";
                    //         $result = $db->query($check_sql);
                    
                    //         if ($result && $result->num_rows == 0) {
                    //             $insert_sql = "INSERT INTO `task_time`(`tid`, `eid`, `pid`, `initial_time`) VALUES ('$tid','$eid','$pid','$timestamp')";
                    //             $db->query($insert_sql);
                    //         } else {
                                
                    //             $insert_sql = "INSERT INTO `task_start_time`(`tid`, `eid`, `pid`, `start_time`) VALUES ('$tid','$eid','$pid','$timestamp')";                 
                    //             $db->query($insert_sql);
                    //         }
                    //     } else {
                    //         echo "Incomplete data for updating time";
                    //     }
                    //     break;


                    case "start_task_time":
                        if(isset($_POST['tid'], $_POST['eid'], $_POST['pid'])) {
                            date_default_timezone_set('Asia/Kolkata');
                            $tid = $_POST['tid'];
                            $eid = $_POST['eid'];
                            $pid = $_POST['pid'];
                            $timestamp = date('h:i:s A');
                            $check_sql = "SELECT * FROM `task_time` WHERE `tid`='$tid' AND `eid`='$eid' AND `pid`='$pid'";
                            $result = $db->query($check_sql);
                    
                            if ($result && $result->num_rows == 0) {
                                $insert_sql = "INSERT INTO `task_time`(`tid`, `eid`, `pid`, `initial_time`) VALUES ('$tid','$eid','$pid','$timestamp')";
                                $db->query($insert_sql);
                            } else {
                                $insert_sql = "INSERT INTO `task_start_time`(`tid`, `eid`, `pid`, `start_time`) VALUES ('$tid','$eid','$pid','$timestamp')";
                                $db->query($insert_sql);
                    
                                // Query to get pause time from task_pause_time
                                $query1 = "SELECT pause_time FROM `task_pause_time` ORDER BY `pause_time` DESC";
                                $result1 = $db->query($query1);
                    
                                // Query to get start_time from task_start_time
                                $query2 = "SELECT start_time FROM `task_start_time` ORDER BY `start_time` DESC";
                                $result2 = $db->query($query2);
                    
                                // Check if both queries executed successfully
                                if ($result1 && $result2) {
                                    // Fetch rows from result sets
                                    $row1 = $result1->fetch_assoc();
                                    $row2 = $result2->fetch_assoc();
                    
                                    // Convert time strings to DateTime objects for calculation
                                    $time1 = new DateTime($row1['pause_time']);
                                    $time2 = new DateTime($row2['start_time']);
                                    $difference = $time1->diff($time2);
                                    $formattedDifference = $difference->format('%H:%I:%S');

                                    // $time1 = strtotime($row1['pause_time']);
                                    // $time2 = strtotime($row1['start_time']);

                                    // $difference_seconds = $time1 - $time2;
                                    // $time_diff = $difference_seconds / 3600; 
                    
                                    // Insert the formatted difference into the database
                                    $insertQuery = "INSERT INTO `time_difference`(`tid`, `eid`, `pid`, `time`) VALUES ('$tid','$eid','$pid','$formattedDifference')";
                                    $db->query($insertQuery);
                                } else {
                                    echo "Error fetching data from the database.";
                                }
                            }
                        } else {
                            echo "Incomplete data for updating time";
                        }
                        break;
                    
                    
                    
                    
                    


                        // pause time
                        case "pause_task_time":
                            if(isset($_POST['tid'], $_POST['eid'] , $_POST['pid']))
                             {
                                date_default_timezone_set('Asia/Kolkata');
                                $tid = $_POST['tid'];
                                $eid = $_POST['eid'];
                                $pid = $_POST['pid'];  
                                $timestamp = date('h:i:s A'); 
                                
                                $sql = "INSERT INTO `task_pause_time`(`tid`, `eid`, `pid`, `pause_time`) VALUES ('$tid','$eid','$pid','$timestamp')";
                                if ($db->query($sql)) 
                                {
                                    // Return success message as JSON
                                    echo json_encode(array("success" => true, "message" => "time added successfully"));
                                } else {
                                    // If an error occurred, send error response
                                    echo json_encode(array("success" => false, "message" => "Error updating project: " . $db->error));
                                }
                            } else {
                                echo "Incomplete data for updating time";
                            }
                            break;



                            // stop time

                            case "stop_task_time":
                                if(isset($_POST['tid'], $_POST['eid'], $_POST['pid'])) {
                                    date_default_timezone_set('Asia/Kolkata');
                                    $tid = $_POST['tid'];
                                    $eid = $_POST['eid'];
                                    $pid = $_POST['pid'];   
                                    $timestamp = date('h:i:s A');
                                    $sql = "UPDATE `task_time` SET `end_time` = '$timestamp' WHERE `tid` = '$tid'";
                                    
                                    if ($db->query($sql) === TRUE) {
                                        // Fetch the initial and end times
                                        $query = "SELECT `initial_time`, `end_time` FROM `task_time` WHERE `tid` = '$tid'";
                                        $result = $db->query($query);
                            
                                        if ($result && $result->num_rows == 1) {
                                            $row = $result->fetch_assoc();
                                            // Calculate the time difference
                                            $start_time = new DateTime($row['initial_time']);
                                            $end_time = new DateTime($row['end_time']);
                                            $difference = $end_time->diff($start_time);
                                            $timeframe = $difference->format('%H:%I:%S'); // Format as needed
                            
                                            // Update total_time in the database
                                            $update_query = "UPDATE `task_time` SET `total_time` = '$timeframe' WHERE `tid` = '$tid'";
                                            
                                            if ($db->query($update_query) === TRUE) {
                                                echo json_encode(array("success" => true, "message" => "Time updated successfully."));
                                            } else {
                                                echo json_encode(array("success" => false, "message" => "Error updating total time: " . $db->error));
                                            }
                                        } else {
                                            echo json_encode(array("success" => false, "message" => "No matching record found."));
                                        }
                                    } else {
                                        echo json_encode(array("success" => false, "message" => "Error updating end time: " . $db->error));
                                    }
                                } else {
                                    echo "Incomplete data for updating time";
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
