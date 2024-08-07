<?php 
session_start();
require("db.php");
require("mail.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$operation = $_POST['ops'];
function generateUsername($firstName, $lastName) {
  // Generate a random number between 1000 and 9999
  $randomNumber = rand(1000, 9999);
    
  // Generate username by concatenating first name, last name, and random number
  $username = strtolower($firstName[0] . $lastName . $randomNumber);
  return $username;
}

function generatePassword($length = 8) {
  // Generate random password
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $password = '';
  $charLength = strlen($characters);
  for ($i = 0; $i < $length; $i++) {
      $password .= $characters[rand(0, $charLength - 1)];
  }
  return $password;
}


switch ($operation) {
    //project Add
    case "project":

      $cid = $_POST['cid'];
      $pname = $_POST['pname'];
      $desc = $_POST['description'];
      $created_at = date('y-m-d H:i:s');

      $sql = "INSERT INTO projects (cid, project_name, description, created_at) VALUES ('$cid','$pname', '$desc', '$created_at')";
      if ($db->query($sql) === TRUE) {
        echo "Project Added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $db->error;
        }
        // Close dbection
        $db->close();

      break;

      //task add
    case "task":
    $pname = $_POST['pname'];
    $desc = htmlspecialchars($_POST['description']);
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $ttype = $_POST['ttype'];
    $status = $_POST['status'];
    // $etime = $_POST['etime'];
    $etime = isset($_POST['etime']) ? $_POST['etime'] : null; 
    // $estimated_time = ($etime != "") ? date("h:i A", strtotime($etime)) : null; 
    $title = htmlspecialchars($_POST['title']);
    $priority = $_POST['priority'];
    $eid = $_POST['eid'];
    $project_type = $_POST['project_type'];
    $created_at = date('y-m-d H:i:s');

    $sql = "INSERT INTO task (start_date, end_date, task_type, eid, pid, title, description, status, estimated_time, priority, project_type, created_at) VALUES
            ('$sdate', '$edate','$ttype', '$eid', '$pname', '$title', '$desc', '$status', '$etime', '$priority', '$project_type', '$created_at')";
    if ($db->query($sql) === TRUE) {
        echo "Task Added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $db->error;
    }
    break;


        //Register Employee
      case "register":
        $empid = $_POST['empid'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $department = $_POST['department'];
        $designation = $_POST['designation'];
        $created_at = date('y-m-d H:i:s');
        $username = generateUsername($fname, $lname);
        $password = generatePassword($length = 8);
        $role = "1";

        $sql = "INSERT INTO employees (emp_id, fname, lname, email, phone, designation, department, username, password, role, created_at) VALUES
        ('$empid', '$fname','$lname', '$email', '$phone', '$designation', '$department', '$username', '$password', '$role', '$created_at')";
        if ($db->query($sql) === TRUE) {
          welcomeEmail($email,$fname,$lname,$username,$password);
          echo "Employee Added successfully & Email Sent";
          } else {
              echo "Error: " . $sql . "<br>" . $db->error;
          }
          // Close dbection
          $db->close();
  
        break;



        // Assign Projects

        case "assign_project":
          $project_id = $_POST['project_id'];
          $employee_id = $_POST['employee_id'];
  
          $sql = "INSERT INTO project_assign (pid, eid) VALUES('$project_id', '$employee_id')";
          if ($db->query($sql) === TRUE) 
            {
            echo "Project Assigned successfully";
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $db->error;
            }
            // Close dbection
            $db->close();
    
          break;

    default:
      echo "Bad Gateway";
  }


}else{
    echo"Bad Request";
}

?>