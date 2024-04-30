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
            
                // Assuming $db is your database connection
                $sql = "SELECT task_time.total_time, time_difference.time, time_difference.reason 
                        FROM task_time 
                        INNER JOIN time_difference ON task_time.tid = time_difference.tid 
                        WHERE task_time.tid = '$tid'";
                $query = mysqli_query($db, $sql);          
                if ($query && mysqli_num_rows($query) > 0) 
                {
                    $row = mysqli_fetch_assoc($query);   
                    // Calculate Time Frame
                    list($total_hours, $total_minutes, $total_seconds) = explode(':', $row["total_time"]);
                    list($difference_hours, $difference_minutes, $difference_seconds) = explode(':', $row["time"]);
            
                    $totaltime_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;
                    $difference_seconds = $difference_hours * 3600 + $difference_minutes * 60 + $difference_seconds;
            
                    $difference_seconds = $totaltime_seconds - $difference_seconds;
                    $timeframe = round($difference_seconds / 60);
            
                    // Adding the calculated timeframe to the $row array
                    $row['timeframe'] = $timeframe;
            
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



        case "view_total_break_time":
            if(isset($_GET['tid'])) 
            {
                $tid = $_GET['tid'];
      // $sql = "SELECT tid, SEC_TO_TIME(SUM(TIME_TO_SEC(time))) AS total_break_time FROM time_difference WHERE tid = '$tid';";
        $sql = "SELECT  tid, CASE WHEN FLOOR(SUM(TIME_TO_SEC(time)) / 3600) > 0 THEN CONCAT(FLOOR(SUM(TIME_TO_SEC(time)) / 3600), 'h ')
            ELSE '' END, CONCAT(FLOOR(MOD(SUM(TIME_TO_SEC(time)), 3600) / 60), 'm') AS total_break_time 
            FROM time_difference WHERE tid = '$tid';";
                $query = mysqli_query($db, $sql);
            
                if ($query && mysqli_num_rows($query) > 0) 
                {
                    $row = mysqli_fetch_assoc($query);
            
                    // Return the data as JSON
                    echo json_encode($row);
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


        case "view_breaks":
            if(isset($_GET['tid'])) 
            {
                $tid = $_GET['tid'];
            
                // Assuming $db is your database connection
                $sql = "SELECT tid, time, reason FROM `time_difference` WHERE tid = '$tid';";
                $query = mysqli_query($db, $sql);
            
                if ($query && mysqli_num_rows($query) > 0) {
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

