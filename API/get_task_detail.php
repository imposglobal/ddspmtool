<?php
session_start();
require("db.php");
require("mail.php");

if(isset($_GET['ops']))
{
    $operation =$_GET['ops'];

    switch ($operation)
    {

        // view total time 
        case "view_time":
            if(isset($_GET['tid']))
            {
                $tid = $_GET['tid'];
                $sql1 = "SELECT * FROM `task_time` WHERE tid = '$tid'";
                $sql2 = "SELECT SUBSTRING(SEC_TO_TIME(SUM(TIME_TO_SEC(time))), 1, 8) AS total_break_time FROM time_difference WHERE tid = '$tid'";
                $query1 = mysqli_query($db, $sql1);
                $query2 = mysqli_query($db, $sql2);        
                if ($query1 && $query2) 
                {                
                    $row1 = mysqli_fetch_assoc($query1);
                    $row2 = mysqli_fetch_assoc($query2);

                    // splitting the values stored in $row["total_time"] and $row["total_break_time"] into arrays

                    // for total task time
                    list($total_hours, $total_minutes, $total_seconds) = explode(':', $row1["total_time"]);
                    // for total break time
                    list($break_hours, $break_minutes, $break_seconds) = explode(':', $row2["total_break_time"]);
            
                    // convert hrs and minuts in seconds for total_time 
                    // 1 minute = 1 * 60 
                    // 1 hr = 60 * 60
                    $totaltime_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;

                    // convert hrs and minuts in seconds for total_break_time
                    // 1 minute = 1 * 60 
                    // 1 hr = 60 * 60
                    $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;
            
                    $total_timeframe = $totaltime_seconds - $total_break_time_seconds;
                    $timeframe = round($total_timeframe / 60);

                    // echo $timeframe;                    
                    // Return the data as JSON
                    echo json_encode(array('total_time' => $row1['total_time'], 'total_break_time' => $row2['total_break_time'], 'time_frame' => $timeframe));                   
                } 
                else
                {
                    // Handle case when no data found
                    echo json_encode(array('error' => 'No data found'));
                }
            } 
            else 
            {
                // Handle case when 'tid' parameter is not set
                echo json_encode(array('error' => 'tid parameter is missing'));
            }           
        break;


        // code to retrieve total break time for a  particular task ID
        case "view_total_break_time":
            if(isset($_GET['tid'])) 
            {
            $tid = $_GET['tid'];
            // this query calculates the total break time in hours and checks if it's greater than 0. If it is, it converts the total break time to hours and concatenates it with the string 'h '. If not, it returns an empty string.

            $sql = "SELECT tid, CASE WHEN FLOOR(SUM(TIME_TO_SEC(time)) / 3600) > 0 THEN CONCAT(FLOOR(SUM(TIME_TO_SEC(time)) / 3600), 'h') ELSE ''
                    END AS hours, CONCAT(FLOOR(MOD(SUM(TIME_TO_SEC(time)), 3600) / 60), 'm') AS minutes FROM time_difference WHERE tid = '$tid'";
            
                $query = mysqli_query($db, $sql);
                if ($query && mysqli_num_rows($query) > 0) 
                {
                    $row = mysqli_fetch_assoc($query);

                    // Check if total break time is zero
                    if(empty($row['hours']) && $row['minutes'] == '0m') 
                    {
                        // If total break time is zero, set 'no breaks' as the total break time
                        $row['total_break_time'] = 'No Breaks';
                    } 
                    else 
                    {
                        // Otherwise, concatenate hours and minutes
                        $row['total_break_time'] = $row['hours'] ." ". $row['minutes'];
                    }
            
                     // Return the data as JSON
                     echo json_encode($row);
                } 
                else 
                {
                    // Handle case when no data found
                    echo json_encode(array('error' => 'No data found'));
                }
            } 
            else 
            {
                // Handle case when 'tid' parameter is not set
                echo json_encode(array('error' => 'tid parameter is missing'));
            }           
        break;



        // Code to retrieve all breaks for a particular task ID.
        case "view_breaks":
            if(isset($_GET['tid'])) 
            {
                $tid = $_GET['tid'];
                // $sql = "SELECT tid, time, reason FROM `time_difference` WHERE tid = '$tid';";
                $sql = "SELECT tid, time, reason FROM `time_difference` WHERE tid = '$tid' AND time != '00:00:00';";
                $query = mysqli_query($db, $sql);
            
                if ($query && mysqli_num_rows($query) > 0) 
                {
                    $rows = array(); // Array to store all fetched rows
                    while ($row = mysqli_fetch_assoc($query)) {
                        // Append each row to the array
                        $rows[] = $row;
                    }       
                    // Return the data as JSON
                    echo json_encode($rows);
                } else {
                    // Handle case when no data found
                    echo json_encode(array('error' => 'No data found'));
                }
            } 
            else 
            {
                // Handle case when 'tid' parameter is not set
                echo json_encode(array('error' => 'tid parameter is missing'));
            }           
        break;       
    }
}
?>

