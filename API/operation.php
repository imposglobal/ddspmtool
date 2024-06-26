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
            echo '<td><a href="'.$base_url.'/Dashboard/employee/user-profile.php?eid='. $row["eid"].'"><i class="icon bi bi-info-circle-fill "></i></a> <i class="icon bi bi-pencil-square"></i> <i onclick="deleteUser('. $row["eid"].')" class="icon text-danger bi bi-trash3"></i></td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
    
}




//  Get Weekly and monthly Working days in working days card
function MonthlyWorkingDays() {
    $currentDate = strtotime('first day of this month');
    $today = strtotime('today');
    $workingDays = 0;

    while ($currentDate <= $today) {
        // Check if the current day is not a Saturday (6) or Sunday (7)
        if (date('N', $currentDate) < 6) {
            $workingDays++;
        }
        // Move to the next day
        $currentDate = strtotime('+1 day', $currentDate);
    }

    return $workingDays;
}

// to calculate all days including sat and sun
// function getWorkingDays() {
//     $currentDate = strtotime('first day of this month');
//     $today = strtotime('today');
//     $totalDays = 0;

//     while ($currentDate <= $today) {
//         // Count every day including Saturdays and Sundays
//         $totalDays++;
//         // Move to the next day
//         $currentDate = strtotime('+1 day', $currentDate);
//     }

//     return $totalDays;
// }









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
$tilldateyWorkingDays = MonthlyWorkingDays();
$totalMonthlyWorkingDays = getMonthlyWorkingDays();


function getMonthlyWorkingDaysCountdown() {
    $currentDate = strtotime('first day of this month');
    $today = strtotime('today');
    $lastDayOfMonth = strtotime('last day of this month');
    $totalWorkingDays = 0;
    $remainingWorkingDays = 0;

    // Loop through each day of the month
    while ($currentDate <= $lastDayOfMonth) {
        // Check if the current day is not a Saturday or Sunday
        if (date('N', $currentDate) < 6) {
            $totalWorkingDays++;
            // Count remaining working days from today
            if ($currentDate >= $today) {
                $remainingWorkingDays++;
            }
        }
        // Move to the next day
        $currentDate = strtotime('+1 day', $currentDate);
    }

    $countdownMessage = "$remainingWorkingDays days to go";
    return [
        'totalWorkingDays' => $totalWorkingDays,
        'remainingWorkingDays' => $remainingWorkingDays,
        'countdownMessage' => $countdownMessage,
    ];
}

// Example usage
$workingDaysInfo = getMonthlyWorkingDaysCountdown();
echo "Total working days this month: " . $workingDaysInfo['totalWorkingDays'] . "\n";
echo "Remaining working days this month: " . $workingDaysInfo['remainingWorkingDays'] . "\n";
echo "Countdown message: " . $workingDaysInfo['countdownMessage'] . "\n";




?>
