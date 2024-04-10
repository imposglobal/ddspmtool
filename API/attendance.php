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
    
        default:
          echo "Bad Gateway";
      }
    

}
