<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
   
    $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : '';
    $employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : '';
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
    fputcsv($output, array('No', 'Project Name', 'Employee Name', 'Date', 'Task Name', 'Status', 'Time Frame', 'Priority', 'Manager Status', 'Feedback', 'Description'));

    // Construct the SQL query with filters
    $sql = "SELECT task.tid, DATE(task.created_at) as created_date, projects.project_name, task.title, task.status, 
     task.estimated_time, task.priority, task.description, task.m_status, task.feedback, employees.fname, employees.lname, task_time.total_time
     FROM task 
     LEFT JOIN employees ON task.eid = employees.eid
     INNER JOIN projects ON task.pid = projects.pid
     INNER JOIN task_time ON task.tid = task_time.tid";

    // Initialize where conditions array
    $where_conditions = [];

    // Append filters to the query if a specific project is selected
    if ($project_id !== 'All') {
        $where_conditions[] = "task.pid = '$project_id'";
    }

    if ($employee_id !== 'All') {
        $where_conditions[] = "task.eid = '$employee_id'";
    }

    // Add conditions based on the selected status
    switch ($time_status) 
    {
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
    if (!empty($where_conditions)) 
    {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    $sql .= " ORDER BY task.created_at DESC";

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
                htmlspecialchars($row["created_date"]),
                $row['title'],
                $row['status'],
                $row['total_time'],
                $row['priority'],
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
