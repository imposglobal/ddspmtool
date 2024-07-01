<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{

    $eid = isset($_GET['eid']) ? $_GET['eid'] : '';
    $pid = isset($_GET['pid']) ? $_GET['pid'] : '';
    $project_type = isset($_GET['project_type']) ? $_GET['project_type'] : '';
    $time_status = isset($_GET['time_status']) ? $_GET['time_status'] : '';
    $task_status = isset($_GET['task_status']) ? $_GET['task_status'] : '';

    // Set headers for CSV export
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=employee_tasks.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen("php://output", "w");

    // Write headers to CSV
    fputcsv($output, array('Project Name', 'Project Type' , 'Employee Name', 'Date', 'Title', 'Description', 'Priority', 'Status', 'Total Time', 'Total Break', 'Productive Time', 'Manager Status', 'Feedback'));

    // Construct the SQL query 
    $sql = "SELECT task.tid, task.pid, task.project_type, task.created_at,  task.start_date, task.title, task.status, task.priority, task.feedback, task.m_status, task.description, projects.project_name, projects.pid,  employees.fname, employees.eid, task_time.total_time, break.total_break
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

 // Initialize where conditions array
 $where_conditions = [];

 // Append filters to the query if a specific project_type is selected
 if ($project_type !== 'All') {
    $where_conditions[] = "task.project_type = '$project_type'";
  }

   // Add conditions based on the selected status
   switch ($time_status) {
    case 'today':
        $where_conditions[] = "DATE(task.created_at) = CURRENT_DATE()";
        break;
    case 'yesterday':
        $where_conditions[] = "DATE(task.created_at) = CURRENT_DATE() - INTERVAL 1 DAY";
        break;
    case 'weekly':
        $where_conditions[] = "task.created_at >= CURRENT_DATE() - INTERVAL 7 DAY";
        break;
    case 'monthly':
        $where_conditions[] = "MONTH(task.created_at) = MONTH(CURRENT_DATE())";
        break;
}

// Add the task status condition if selected
if (!empty($task_status) && $task_status !== 'Select Task Status') {
    $where_conditions[] = "task.status = '$task_status'";
}

// If there are any conditions, they are combined and appended to the SQL query.
if (!empty($where_conditions)) {
    $sql .= " AND " . implode(" AND ", $where_conditions);
}

$sql .= " ORDER BY task.created_at DESC";

$result = mysqli_query($db, $sql);

function RemoveHTMLTags($html) {
    // Decode HTML entities
    $html = html_entity_decode($html);
    // Replace non-breaking space and &amp; with a regular space and &
    // $html = str_replace(['&nbsp;', '&amp;'], [' ', '&'], $html);
    $html = str_replace(['&nbsp;', '&amp;', '-'], [' ', '&', ''], $html);
    // Strip HTML tags
    return strip_tags($html);
}

if ($result)
{
    if (mysqli_num_rows($result) > 0)
    {
        while ($row = mysqli_fetch_assoc($result))
        {
              // Remove HTML tags from the description

              $decode_desc = RemoveHTMLTags($row["description"]);

            /////////////////////////////Calculation//////////////////////////

            list($total_hours, $total_minutes, $total_seconds) = explode(':', $row['total_time']);
            // for total break time
            list($break_hours, $break_minutes, $break_seconds) = explode(':', $row['total_break']);
            // convert hrs and minuts in seconds for total_time 
            // 1 minute = 1 * 60 
            // 1 hr = 60 * 60 =3600
            $total_time_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;

             // convert hrs and minuts in seconds for total_break_time
            // 1 minute = 1 * 60 
            // 1 hr = 60 * 60 = 3600
            $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;

            $total_timeframe = $total_time_seconds - $total_break_time_seconds;
           
            // convert total timeframe back to HH:MM:SS format
            $hours = floor($total_timeframe / 3600);
            $minutes = floor(($total_timeframe % 3600) / 60);
            $seconds = $total_timeframe % 60;
            
            // This line formats the hours, minutes, and seconds into a string in the HH:MM:SS format

            $actual_task_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            $data = array(  
                $row['project_name'], 
                $row['project_type'],             
                $row['fname'],
                htmlspecialchars($row["start_date"]),
                $row['title'],
                $decode_desc,
                $row['priority'],
                $row['status'],                              
                substr($row['total_time'], 0, 8),
                substr($row['total_break'], 0, 8),
                $actual_task_time,
                $row['m_status'],
                $row['feedback'],                          
                
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
