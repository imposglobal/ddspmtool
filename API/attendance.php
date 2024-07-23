<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
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
            // Close dbconnection
            $db->close();
            break;
    
    
        //  add clockout in dashboard

           case "clockout":
            // Set IST Timezone
            date_default_timezone_set('Asia/Kolkata');
            $eid = $_POST['eid'];
            $time = date('h:i:s A'); 
        
            $sql = "SELECT `end_time` FROM `task_time` WHERE `eid` = '$eid' AND `date` = CURDATE();";
            $result = $db->query($sql);
        

  //if the query is successful, it initializes a flag ($allTasksEnded) to true., then iterates through each row of the result set to check if any task has an empty end_time.
 // if any task's end_time is empty, it sets $allTasksEnded to false and breaks out of the loop.

            if ($result) 
            {
                $allTasksEnded = true;
                while ($row = $result->fetch_assoc()) {
                    if (empty($row['end_time'])) {
                        $allTasksEnded = false;
                        break;
                    }
                }
        
                if ($allTasksEnded) {
                    $sql = "UPDATE `attendance` SET `logout_time`= '$time' WHERE `eid` = '$eid' AND `date` = CURDATE()";
                    if ($db->query($sql)) {
                        echo json_encode(['status' => 'success']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update logout time']);
                    }
                } 
                else {
                    echo json_encode(['status' => 'error', 'message' => 'Make sure to complete and stop your task before clocking out']);
                }
            } 
            else {
                echo json_encode(['status' => 'error', 'message' => 'Database query failed: ' . $db->error]);
            }
        
            // Close db connection
            $db->close();
            break;

        
        default:
        echo "Bad Gateway";

}
}

// Get attendance in attendance page with pagination

function get_attendance($role, $eid, $db, $start_date, $end_date, $current_page, $records_per_page)
{
    // Calculate offset
    $offset = ($current_page - 1) * $records_per_page;
    $date = date("Y-m-d");

    // SQL query based on role and date range
    if (isset($_GET["get_date"])) 
    {
        // Filter by provided start_date and end_date
        if ($role == 0) {
            $sql = "SELECT attendance.eid, attendance.login_time, attendance.logout_time, attendance.date, employees.fname, employees.lname
                    FROM attendance
                    INNER JOIN employees ON attendance.eid = employees.eid
                    WHERE DATE(attendance.date) BETWEEN '$start_date' AND '$end_date'
                    ORDER BY attendance.date DESC
                    LIMIT $records_per_page OFFSET $offset";
        } else {
            $sql = "SELECT attendance.eid, attendance.login_time, attendance.logout_time, attendance.date, employees.fname, employees.lname
                    FROM attendance
                    INNER JOIN employees ON attendance.eid = employees.eid
                    WHERE DATE(attendance.date) BETWEEN '$start_date' AND '$end_date' AND attendance.eid = '$eid'
                    ORDER BY attendance.date DESC
                    LIMIT $records_per_page OFFSET $offset";
        }
    } else {
        // Filter by today's date
        if ($role == 0) {
            $sql = "SELECT attendance.eid, attendance.login_time, attendance.logout_time, attendance.date, employees.fname, employees.lname
                    FROM attendance
                    INNER JOIN employees ON attendance.eid = employees.eid
                    WHERE DATE(attendance.date) = '$date'
                    ORDER BY attendance.date DESC
                    LIMIT $records_per_page OFFSET $offset";
        } else {
            $sql = "SELECT attendance.eid, attendance.login_time, attendance.logout_time, attendance.date, employees.fname, employees.lname
                    FROM attendance
                    INNER JOIN employees ON attendance.eid = employees.eid
                    WHERE DATE(attendance.date) = '$date' AND attendance.eid = '$eid'
                    ORDER BY attendance.date DESC
                    LIMIT $records_per_page OFFSET $offset";
        }
    }

    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $i = 1 + $offset;
        while ($row = mysqli_fetch_assoc($result)) {

            $eid =  $row['eid'];
            $date = $row["date"];

            // Initialize time variables
            $total_time = '';
            $productive_time = '';

            // Calculate the difference between login_time and logout_time
            if (!empty($row["login_time"]) && !empty($row["logout_time"])) 
            {
            $login_time = strtotime($row["login_time"]);
            $logout_time = strtotime($row["logout_time"]);
            $time_difference = $logout_time - $login_time;
            $total_time = gmdate('H:i:s', $time_difference);

            // Fetch total day task for the current date
            $sql1 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(task_time.total_time))) AS total_day_task
                     FROM task_time
                     WHERE eid = '$eid' AND task_time.date = '$date'";
            $result1 = mysqli_query($db, $sql1);
            $row1 = mysqli_fetch_assoc($result1);

             // Fetch break time for the current date
            $sql2 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS total_day_break
                     FROM time_difference
                     WHERE eid = '$eid' AND time_difference.date = '$date' AND time_difference.reason != 'Other-Task'";

           
            $result2 = mysqli_query($db, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

            // Calculate the difference between task time and break time
            $total_day_task = isset($row1["total_day_task"]) ? strtotime($row1["total_day_task"]) : 0;
            $total_day_break = isset($row2["total_day_break"]) ? strtotime($row2["total_day_break"]) : 0;
            $actual_time = $total_day_task - $total_day_break;
            $productive_time = gmdate('H:i:s',  $actual_time);

        }
       
            echo '<tr>';
            echo '<th scope="row">' . $i++ . '</th>';
            echo '<td>' . $row["fname"] . " " . $row["lname"] . '</td>';
            echo '<td>' . $row["date"] . '</td>';
            echo '<td>' . $row["login_time"] . '</td>';
            echo '<td>' . $row["logout_time"] . '</td>';
            echo '<td>' . $total_time . '</td>';
            echo '<td>' . $productive_time . '</td>';
            echo '</tr>';
       
        }
    }
}
?>
