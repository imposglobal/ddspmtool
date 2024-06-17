<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture filter parameters
    $project_id = isset($_POST['project_id']) ? $_POST['project_id'] : '';

    // Set headers for CSV export
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=project-report.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen("php://output", "w");
    

    // Write headers to CSV
    fputcsv($output, array('No', 'Project Name', 'Employee Name', 'Date', 'Task Name', 'Status', 'Priority', 'Total Time', 'Total Break Time', 'Manager Status', 'Feedback', 'Description'));

    // Construct the SQL query with filters
    $sql = "SELECT projects.project_name, task.tid, task.created_at, task.start_date, task.title, task.status, 
        task.estimated_time, task.priority, task.description, task.m_status, task.feedback, employees.fname, employees.lname, task_time.total_time
        FROM task 
        LEFT JOIN employees ON task.eid = employees.eid
        INNER JOIN projects ON task.pid = projects.pid
        INNER JOIN task_time ON task.tid = task_time.tid";


   

    // Append filters to the query if a specific project is selected
    if ($project_id !== 'All')
    {
        $sql .= " WHERE task.pid = '$project_id'";
    }

    // Execute the query
    $result = mysqli_query($db, $sql);

    // Check for errors
    if (!$result) {
        die('Error: ' . mysqli_error($db));
    }

    // Fetch and write data to CSV
    if (mysqli_num_rows($result) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {

            $tid = $row["tid"];

            $sql1 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS total_task_break FROM time_difference WHERE tid = '$tid' AND time != '00:00:00'";
            $result1 = mysqli_query($db, $sql1);
            $row1 = mysqli_fetch_assoc($result1);


            // time calculation

            $total_task_time =  strtotime($row['total_time']);
            $total_task_break_time = strtotime($row1['total_task_break']);
            $Actual_task_time = $total_task_time - $total_task_break_time;
            $task_time = gmdate('H:i:s', $Actual_task_time);

            // feedback
            $removehtmltags = strip_tags($row["feedback"]);
            $decode_feedback = html_entity_decode($removehtmltags);

            // Remove HTML tags from the description
            $removedesc = strip_tags($row["description"]);
            $decode_desc = html_entity_decode($removedesc);
            
            $data = array(
                $i++,
                $row['project_name'],
                $row['fname'] . " " . $row['lname'],
                htmlspecialchars($row["start_date"]),
                $row['title'],
                $row['status'],
                $row['priority'],
                $task_time,
                substr($row1['total_task_break'], 0, 8),            
                $row['m_status'],               
                $decode_feedback,
                $decode_desc
            );
            fputcsv($output, $data);
        }
    } else {
        // If no results found, echo message
        echo "No records found for the specified criteria.";
    }

    // Close output stream
    fclose($output);

    // Exit script
    exit;
}
?>
