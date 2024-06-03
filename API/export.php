<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture filter parameters
    $project_id = isset($_POST['project_id']) ? $_POST['project_id'] : '';
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

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
    // $sql = "SELECT task.tid, task.created_at, projects.project_name, task.title, task.status, 
    //         task.estimated_time, task.priority, employees.fname, employees.lname, task_time.total_time
    //         FROM task 
    //         LEFT JOIN employees ON task.eid = employees.eid
    //         INNER JOIN projects ON task.pid = projects.pid
    //         INNER JOIN task_time ON task.tid = task_time.tid
    //         WHERE task.pid = '$project_id'";

    $sql = "SELECT task.tid, DATE(task.created_at) as created_date, projects.project_name, task.title, task.status, 
        task.estimated_time, task.priority, task.description, task.m_status, task.feedback, employees.fname, employees.lname, task_time.total_time
        FROM task 
        LEFT JOIN employees ON task.eid = employees.eid
        INNER JOIN projects ON task.pid = projects.pid
        INNER JOIN task_time ON task.tid = task_time.tid Order BY task.created_at DESC ";

    // Append filters to the query if a specific project is selected
    if ($project_id !== 'All') 
    {
    $sql .= " WHERE task.pid = '$project_id'";
    }

    // Append filters to the query
    if (!empty($start_date)) {
        $sql .= " AND task.created_at >= '$start_date'";
    }
    if (!empty($end_date)) {
        $sql .= " AND task.created_at <= '$end_date'";
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

            // feedback

             // removes any HTML tags from it using the strip_tags()
        $removehtmltags = strip_tags($row["feedback"]);
        // decodes HTML entities back into their respective characters.
        $decode_feedback = html_entity_decode($removehtmltags);

           // Remove HTML tags from the description
        $removedesc = strip_tags($row["description"]);
        // Decode HTML entities back into their respective characters
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