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
                    if(isset($_POST['tid'], $_POST['sdate'], $_POST['edate'], $_POST['status'], $_POST['task_type'], $_POST['title'], $_POST['etime'], $_POST['priority'], $_POST['editor1'])) {
                        $tid = $_POST['tid'];
                        $sdate = $_POST['sdate'];
                        $edate = $_POST['edate'];
                        $task_type = $_POST['task_type'];
                        $title = htmlspecialchars($_POST['title']);
                        $status = $_POST['status'];
                        $estimated_time = $_POST['etime'];
                        // $estimated_time = date("h:i A", strtotime($_POST['etime']));
                        $priority = $_POST['priority'];
                        $editor1 = htmlspecialchars($_POST['editor1']);
    
                        $sql = "UPDATE task SET start_date = '$sdate', end_date = '$edate', task_type = '$task_type', title = '$title', status = '$status', estimated_time = '$estimated_time', priority = '$priority', description = '$editor1' WHERE tid = '$tid'";
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
                    if(isset($_POST['tid'], $_POST['eid'], $_POST['m_status'], $_POST['feedback']))
                     {
                        $tid = $_POST['tid'];
                        $eid = $_POST['eid'];
                        $m_status= $_POST['m_status'];
                        $feedback= htmlspecialchars($_POST['feedback']); 
                        $sql = "UPDATE task SET m_status = '$m_status', feedback = '$feedback'  WHERE tid = '$tid' AND eid = '$eid'";
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
                    // code for start task



                    case "start_task_time":
                        $response = array();
                        // Check if all required POST parameters are set
                        if(isset($_POST['tid'], $_POST['eid'], $_POST['pid'])) 
                        {
                            // Retrieve POST data
                            $tid = $_POST['tid'];
                            $eid = $_POST['eid'];
                            $pid = $_POST['pid'];
                        // for curretnt time
                            date_default_timezone_set('Asia/Kolkata');
                            $timestamp = date('h:i:s A');
                        // for current date
                            $date = date('Y-m-d');   
                            // Check if task time entry already exists
                            $check_sql = "SELECT * FROM `task_time` WHERE `tid`='$tid' AND `eid`='$eid' AND `pid`='$pid' AND `date`='$date'";
                            $result = $db->query($check_sql);
                        
                            if($result && $result->num_rows == 0) 
                            {
                                // If no entry exists, insert task time
                                $insert_sql = "INSERT INTO `task_time`(`tid`, `eid`, `pid`, `initial_time`, `date`) VALUES ('$tid','$eid','$pid','$timestamp', '$date')";
                                if($db->query($insert_sql)) 
                                {
                                    $response['success'] = true;
                                    $response['message'] = "Task time inserted successfully.";
                                } 
                                else 
                                {
                                    $response['success'] = false;
                                    $response['message'] = "Error inserting task time.";
                                }
                            } 
                            else 
                            {
                                // If entry exists, insert start time and calculate difference
                                $insert_sql = "INSERT INTO `task_start_time`(`tid`, `eid`, `pid`, `start_time`, `date`) VALUES ('$tid','$eid','$pid','$timestamp','$date')";
                                if($db->query($insert_sql)) 
                                {
    
                                    $query1 = "SELECT * FROM `task_pause_time` WHERE eid = '$eid' AND tid = '$tid' AND date = '$date' ORDER BY `id` DESC LIMIT 1";
                                    $result1 = $db->query($query1);            
                                    $query2 = "SELECT * FROM `task_start_time` WHERE eid = '$eid' AND tid = '$tid' AND date = '$date' ORDER BY `id` DESC LIMIT 1";
                                    $result2 = $db->query($query2);
                                    if ($result1 && $result2 && $result1->num_rows > 0 && $result2->num_rows > 0) 
                                    {
                                        $row1 = $result1->fetch_assoc();
                                        $row2 = $result2->fetch_assoc();           
                                        $reason = $row1['reason'];
                                        // creates a new DateTime object $time1 using the value of $row1['pause_time']
                                        $time1 = new DateTime($row1['pause_time']);
                                        $time2 = new DateTime($row2['start_time']);
                                        // calculates the difference between the two DateTime objects $time1 and $time2
                                        $difference = $time1->diff($time2);
                                        // %H:%I:%S' specifies that the output should be in the format of hours, minutes, and seconds. 
                                        $formattedDifference = $difference->format('%H:%I:%S');
                        
                                        $insertQuery = "INSERT INTO `time_difference`(`tid`, `eid`, `pid`, `time`, `reason`, `date`) VALUES ('$tid','$eid','$pid','$formattedDifference', '$reason', '$date')";
                                        if($db->query($insertQuery)) 
                                        {
                                            $response['success'] = true;
                                            $response['message'] = "Task start time inserted successfully.";
                                        } 
                                        else 
                                        {
                                            $response['success'] = false;
                                            $response['message'] = "Error inserting time difference.";
                                        }
                                    }
                                    else 
                                    {
                                        $response['success'] = false;
                                        $response['message'] = "Error fetching data.";
                                    }
                                } 
                                else 
                                {
                                    $response['success'] = false;
                                    $response['message'] = "Error inserting task start time.";
                                }
                            }
                        } 
                        else 
                        {
                            $response['success'] = false;
                            $response['message'] = "Incomplete data for updating time.";
                        }
                        
                        // Output the response as JSON
                        echo json_encode($response);
                        break;
                    
                    

                        // code for pause time button
                        case "pause_task_time":
                            $response = array();
                            // Check if all required POST parameters are set
                            if (isset($_POST['tid'], $_POST['eid'], $_POST['pid'] , $_POST['reason'])) 
                            {
                                // Retrieve POST data
                                $tid = $_POST['tid'];
                                $eid = $_POST['eid'];
                                $pid = $_POST['pid'];
                                $reason = $_POST['reason'];
                                date_default_timezone_set('Asia/Kolkata');
                                $timestamp = date('h:i:s A');
                                $date = date('Y-m-d');
                        
                                // Insert pause time into the database
                                $sql = "INSERT INTO `task_pause_time`(`tid`, `eid`, `pid`, `pause_time`, `reason`, `date`) VALUES ('$tid','$eid','$pid','$timestamp','$reason','$date')";
                                if ($db->query($sql)) 
                                {
                                    $response['success'] = true;
                                    $response['message'] = "Task paused successfully.";
                                } 
                                else 
                                {
                                    // If there's an error executing the SQL query
                                    $response['success'] = false;
                                    $response['message'] = "Error inserting pause time: " . $db->error;
                                }
                            } 
                            else 
                            {
                                $response['success'] = false;
                                $response['message'] = "Incomplete data for pausing time.";
                            }
                        
                            // Output the response as JSON
                            echo json_encode($response);
                            break;



                
                      // stop time


                      case "stop_task_time":
                        $response = array();
                        if(isset($_POST['tid'], $_POST['eid'], $_POST['pid']))
                        {
                            date_default_timezone_set('Asia/Kolkata');
                            $tid = $_POST['tid'];
                            $eid = $_POST['eid'];
                            $pid = $_POST['pid']; 
                            $timestamp = date('h:i:s A');
                            $date = date('Y-m-d');
                            // Insert default time difference
                            $sql2 = "INSERT INTO `time_difference`(`tid`, `eid`, `pid`, `time`, `reason`, `date`) VALUES ('$tid','$eid','$pid','00:00:00','no-breaks','$date')";
                            if($db->query($sql2))
                            {
                                 // Update end time
                                 $sql1 = "UPDATE `task_time` SET `end_time` = '$timestamp' WHERE `eid` = '$eid' AND `tid` = '$tid'";
                                 if ($db->query($sql1))
                                 {
                                  // Calculate time difference
                                  $query = "SELECT * FROM `task_time` WHERE `eid` = '$eid' AND `tid` = '$tid'";
                                  $result = $db->query($query);
                                  if ($result && $result->num_rows == 1)
                                  {
                                    $row = $result->fetch_assoc();
                                    $start_time = new DateTime($row['initial_time']);
                                    $end_time = new DateTime($row['end_time']);
                                    $difference = $end_time->diff($start_time);
                                    $timeframe = $difference->format('%H:%I:%S');

                                    // Update total_time

                                    $update_query = "UPDATE `task_time` SET `total_time` = '$timeframe' WHERE `eid` = '$eid' AND `tid` = '$tid'";
                                    if ($db->query($update_query))
                                    {
                                        $response['success'] = true;
                                        $response['message'] = "Task stopped successfully.";
                                    }
                                    else 
                                    {
                                        $response['success'] = false;
                                        $response['message'] = "Error updating total time.";
                                    }

                                  }
                                  else 
                                  {
                                    $response['success'] = false;
                                    $response['message'] = "Error fetching task time.";
                                  }

                                 }
                                 else 
                                 {
                                    $response['success'] = false;
                                    $response['message'] = "Error updating end time.";
                                 }
                            }
                            else 
                            {
                                $response['success'] = false;
                                $response['message'] = "Error inserting default time difference.";
                            }
                        }
                        else 
                        {
                            $response['success'] = false;
                            $response['message'] = "Incomplete data for stopping time.";
                        }
                         // Output the response as JSON
                        echo json_encode($response);
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
