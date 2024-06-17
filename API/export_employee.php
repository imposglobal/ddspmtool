<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{

    $eid = isset($_POST['eid']) ? $_POST['eid'] : '';
    $pid = isset($_POST['pid']) ? $_POST['pid'] : '';
    // Set headers for CSV export
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=employee_tasks.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen("php://output", "w");

    // Write headers to CSV
    fputcsv($output, array('Project Name', 'Employee Name', 'Start Date', 'Task Name', 'Status', 'Priority', 'Task Time', 'Task Break', 'Manager Status', 'Feedback', 'Description'));

    // Construct the SQL query 
    $sql = "SELECT task.tid, task.pid, task.created_at,  task.start_date, task.title, task.status, task.priority, task.feedback, task.m_status, task.description, projects.project_name, projects.pid,  employees.fname, employees.eid, task_time.total_time, break.total_break
    FROM task
    INNER JOIN employees ON task.eid = employees.eid
    INNER JOIN projects ON task.pid = projects.pid 
    INNER JOIN task_time ON task.tid = task_time.tid AND task.eid = task_time.eid 
    INNER JOIN 
      -- subquery to fetch total break time
( SELECT 
tid, 
eid, 
pid, 
SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS total_break
FROM 
time_difference 
GROUP BY 
tid, eid, pid
) AS break
ON 
task.tid = break.tid 
AND task.eid = break.eid 
AND task.pid = break.pid
WHERE task.eid = '$eid' AND task.pid = '$pid'";

$result = mysqli_query($db, $sql);

if ($result)
{
    if (mysqli_num_rows($result) > 0)
    {
        while ($row = mysqli_fetch_assoc($result))
        {
              // Remove HTML tags from the description
              $removedesc = strip_tags($row["description"]);
              // Decode HTML entities back into their respective characters
              $decode_desc = html_entity_decode($removedesc);

              // Convert total_time and total_break to seconds
            //   $total_time = strtotime($row['total_time']);
            //   $total_break_time = strtotime($row['total_break']);
            //    $actual_time = $total_time - $total_break_time;
            //    $effective_time = gmdate('H:i:s', $actual_time); 

            /////////////////////////////Calculation//////////////////////////

            list($total_hours, $total_minutes, $total_seconds) = explode(':', $row['total_time']);
            // for total break time
            list($break_hours, $break_minutes, $break_seconds) = explode(':', $row['total_break']);
            // convert hrs and minuts in seconds for total_time 
            // 1 minute = 1 * 60 
            // 1 hr = 60 * 60
            $total_time_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;

             // convert hrs and minuts in seconds for total_break_time
            // 1 minute = 1 * 60 
            // 1 hr = 60 * 60
            $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;

            $total_timeframe = $total_time_seconds - $total_break_time_seconds;
           
            // convert total timeframe back to HH:MM:SS format
            $hours = floor($total_timeframe / 3600);
            $minutes = floor(($total_timeframe % 3600) / 60);
            $seconds = $total_timeframe % 60;

            $actual_task_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);




            $data = array(  
                $row['project_name'],              
                $row['fname'],
                htmlspecialchars($row["start_date"]),
                $row['title'],
                $row['status'],               
                $row['priority'],
                $actual_task_time,  
                substr($row['total_break'], 0, 8),
                $row['m_status'],
                $row['feedback'],                          
                $decode_desc
            );
            fputcsv($output, $data);             
        }
    }
    else
    {
        echo "No records found for the specified criteria.";
    }
}

   
   

    // Close output stream
    fclose($output);

    // Exit script
    exit;
}
?>
