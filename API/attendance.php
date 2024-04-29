<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $operation = $_POST['ops'];
    $eid = $_POST['eid'];
    switch ($operation) {
        //check Attendance
        case "checkatt":
          // SQL query
                $date = date('Y-m-d');
                $sql = "SELECT * FROM attendance WHERE login_time IS NOT NULL AND date = '$date' AND eid = '$eid'";

                // Execute query
                $result = $db->query($sql);

                // Check if there are any rows returned
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        // Assuming 'clockin' is the column where you want to set the clock-in time
                        $clockin = $row["login_time"];
                        echo "clockin";
                    }
                }
                // Close dbection
                $db->close();
          break;


       //  add clockin in dashboard
       
        case "clockin": 
            // Set IST Timezone      
            date_default_timezone_set('Asia/Kolkata');
            $eid = $_POST['eid'];
            $time = date('h:i:s A');
            $date = date('Y-m-d');       
            $sql = "INSERT INTO attendance (login_time, eid, date) VALUES ('$time', '$eid', '$date')";       
            if ($db->query($sql) === TRUE) 
            {
              echo "success";
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $db->error;
            }       
            // Close dbection
            $db->close();
            break;
    
    
        //  add clockout in dashboard
        case "clockout":
            // Set IST Timezone
            date_default_timezone_set('Asia/Kolkata');
            $eid = $_POST['eid'];
            $time = date('h:i:s A'); 
                     
            $sql = "UPDATE `attendance` SET `logout_time`='$time' WHERE `eid` = '$eid'";       
            if ($db->query($sql) === TRUE) 
            {
            echo "success";
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
    

}


// Get attendance in attendance page


function get_attendance($role, $eid, $db, $page = 1, $recordsPerPage = 10, $start_date, $end_date)
{
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;
    $date = date("Y-m-d"); 

    // Check if the 'get_date' parameter is set, and if it is, filter the data by the specified date range.
    if(isset($_GET["get_date"])) {
       // If "get_date" is set, filter by provided start_date and end_date
       if ($role == 0) {
        $sql = "SELECT attendance.eid, attendance.login_time, attendance.logout_time, attendance.date, employees.fname, employees.lname, employees.eid FROM attendance INNER JOIN employees ON attendance.eid = employees.eid WHERE DATE(attendance.date) BETWEEN '$start_date' AND '$end_date' ORDER BY attendance.date DESC LIMIT $offset, $recordsPerPage";
    } else {
        $sql = "SELECT attendance.eid, attendance.login_time, attendance.logout_time, attendance.date, employees.fname, employees.lname, employees.eid FROM attendance INNER JOIN employees ON attendance.eid = employees.eid WHERE DATE(attendance.date) BETWEEN '$start_date' AND '$end_date' ORDER BY attendance.date DESC AND attendance.eid = '$eid' LIMIT $offset, $recordsPerPage";
    }
    } else {
// If "get_date" is not set, filter by today's date
  if ($role == 0) {
    $sql = "SELECT attendance.eid, attendance.login_time, attendance.logout_time, attendance.date, employees.fname, employees.lname, employees.eid FROM attendance INNER JOIN employees ON attendance.eid = employees.eid WHERE DATE(attendance.date) = '$date' ORDER BY attendance.date DESC LIMIT $offset, $recordsPerPage";
   } else {
    $sql = "SELECT attendance.eid, attendance.login_time, attendance.logout_time, attendance.date, employees.fname, employees.lname, employees.eid FROM attendance INNER JOIN employees ON attendance.eid = employees.eid WHERE DATE(attendance.date) = '$date' AND attendance.eid = '$eid' ORDER BY attendance.date DESC LIMIT $offset, $recordsPerPage";
   }
    }

    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">' . $i++ . '</th>';
            echo '<td>' . $row["fname"] . " " . $row["lname"] . '</td>';
            echo '<td>' . $row["date"] . '</td>';
            echo '<td>' . $row["login_time"] . '</td>';
            echo '<td>' . $row["logout_time"] . '</td>';
            echo '</tr>';
        }
    }
}



