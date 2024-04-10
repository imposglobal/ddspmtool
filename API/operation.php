<?php
require("db.php");

// Function to view employees details
function get_employees($base_url,$db, $page = 1, $recordsPerPage = 10){
   
    $offset = ($page - 1) * $recordsPerPage;   // Calculate offset
    $sql = "SELECT * FROM employees LIMIT $offset, $recordsPerPage";  // Fetch employees with pagination
    $result = mysqli_query($db, $sql);
    // print_r($result);
    
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>'. $row["fname"]." ". $row["lname"].'</td>';
            echo '<td>'. $row["email"].'</td>';
            echo '<td>'. $row["designation"].'</td>';
            echo '<td>'. $row["department"].'</td>';
            echo '<td><a href="'.$base_url.'/Dashboard/employee/user-profile.php?eid='. $row["eid"].'"><i class="icon bi bi-info-circle-fill "></i></a> <i class="icon bi bi-pencil-square"></i> <i class="icon text-danger bi bi-trash3"></i></td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
    
}




//  Get Weekly and monthly Working days in working days card
function getWeeklyWorkingDays() {
    $currentDate = strtotime('today');
    $workingDays = 0;
    // Check if the current day is not a Saturday or Sunday
    if (date('N', $currentDate) < 6) {
        $workingDays++;
    }
    // Move to the next day and check again until Saturday is reached
    while (date('N', $currentDate) < 6) {
        $currentDate = strtotime('+1 day', $currentDate);
        if (date('N', $currentDate) < 6) {
            $workingDays++;
        }
    }
    return $workingDays;
}

// Get monthly Working days in working days card
function getMonthlyWorkingDays() {
    $currentDate = strtotime('first day of this month');
    $lastDayOfMonth = strtotime('last day of this month');
    $workingDays = 0;
    // Loop through each day of the month
    while ($currentDate <= $lastDayOfMonth) {
        // Check if the current day is not a Saturday or Sunday
        if (date('N', $currentDate) < 6) {
            $workingDays++;
        }
        // Move to the next day
        $currentDate = strtotime('+1 day', $currentDate);
    }

    return $workingDays;
}

// echo these variables in index file to display live days count
$totalWeeklyWorkingDays = getWeeklyWorkingDays();
$totalMonthlyWorkingDays = getMonthlyWorkingDays();







?>
