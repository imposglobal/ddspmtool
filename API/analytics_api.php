<?php
require("db.php");

// code to retrive data from table projects
$query = "SELECT project_name, COUNT(*) AS count FROM projects GROUP BY project_name";
$result = mysqli_query($db,$query);
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['project_name']] = $row['count'];
}
// JSON encode the data for AJAX response
echo json_encode($data);



// function to get project count
function total_project_count($db)
{
  
    $sql = "SELECT COUNT(*) AS project_count FROM projects";
    // Execute the query using the provided database connection object
    $result = $db->query($sql);
    if ($result) {
        if ($result->num_rows > 0) {
            // Fetch the result
            $row = $result->fetch_assoc();
            $projectCount = $row["project_count"];

            // Return the project count
            return $projectCount;
        } else {
            return 0; // No projects found
        }
    } else {
        return -1; // Error executing query
    }
}


// to get task analytics 

function get_task_analytics($db, $page = 1, $recordsPerPage = 10)
{
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;
    
    // Fetch task analytics with pagination
    $sql = "SELECT 
    projects.project_name, 
    projects.status, 
    projects.start_date, 
    projects.end_date, 
    DATE(projects.created_at) AS created_date,
    task.title, 
    task.description, 
    employees.eid, 
    GROUP_CONCAT(DISTINCT CONCAT('<a href=\"employee_task.php?eid=', employees.eid, '&pid=', task.pid, '\">', employees.fname, '</a>') ORDER BY employees.fname SEPARATOR ', ') AS employee_links,
    task_time.pid,
    task_time.tid,
    project_task_count.total_tasks,
    project_employee_count.total_employees
FROM 
    task 
INNER JOIN 
    projects ON task.pid = projects.pid  
INNER JOIN 
    employees ON task.eid = employees.eid 
INNER JOIN 
    task_time ON task.tid = task_time.tid AND task.pid = task_time.pid
INNER JOIN 
    (SELECT pid, COUNT(*) AS total_tasks FROM task GROUP BY pid) AS project_task_count 
    ON projects.pid = project_task_count.pid
INNER JOIN 
    (SELECT pid, COUNT(DISTINCT eid) AS total_employees FROM task GROUP BY pid) AS project_employee_count 
    ON projects.pid = project_employee_count.pid
GROUP BY 
    projects.project_name
ORDER BY 
    created_date DESC 
LIMIT 
    $offset, $recordsPerPage";
  
    $result = mysqli_query($db, $sql);
    
    if (mysqli_num_rows($result) > 0) 
    {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) 
        {
            $eid =  $row["eid"];
            $pid =  $row["pid"]; 
            
            // query to fetch sum of total time of any projects
            $sql1 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(task_time.total_time))) AS total_project_time FROM task_time
            WHERE pid = '$pid'";
            $result1 = mysqli_query($db, $sql1);
            $row1 = mysqli_fetch_assoc($result1);
            // query to fetch total break time of any project
            $sql2 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS total_project_break FROM time_difference WHERE pid = '$pid'";
            $result2 = mysqli_query($db, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

              // Calculate actual time form total_time and total_break 
            //   $total_project_time = strtotime($row1['total_project_time']);
            //   $total_project_break = strtotime($row2['total_project_break']);
            //   $substract_time = $total_project_time - $total_project_break;
            //   $time = gmdate('H:i:s', $substract_time);

            // for total task time
            list($total_hours, $total_minutes, $total_seconds) = explode(':', $row1['total_project_time']);
            // for total break time
            list($break_hours, $break_minutes, $break_seconds) = explode(':', $row2['total_project_break']);
            // convert hrs and minuts in seconds for total task time 
            // 1 minute = 1 * 60 = 60
            // 1 hr = 60 * 60 = 3600
            $total_time_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;
             // convert hrs and minuts in seconds for total break time
            // 1 minute = 1 * 60 = 60
            // 1 hr = 60 * 60 = 3600
            $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;

            // To get the actual time frame, subtract total_break_time_seconds from total_time_seconds

            $total_timeframe = $total_time_seconds - $total_break_time_seconds;
           
            // convert total timeframe back to HH:MM:SS format
            $hours = floor($total_timeframe / 3600);
            $minutes = floor(($total_timeframe % 3600) / 60);
            $seconds = $total_timeframe % 60;

           $actual_task_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>'. $row["project_name"].'</td>';
            echo '<td>'. $row["total_tasks"].'</td>';                 
            echo '<td>'. $row["total_employees"].'</td>';          
            echo '<td>'. $row["employee_links"].'</td>';                    
            // echo '<td>' .substr($row1["total_project_time"], 0, 8).' </td>';
            echo '<td>' . $actual_task_time .'</td>';
            echo '<td>' . substr($row2["total_project_break"], 0, 8) .' </td>';  
                  
            echo '</tr>';
           
        }
    } 
    else 
    {
        echo "<tr><td colspan='4'>No results found.</td></tr>";
    }
}


?>