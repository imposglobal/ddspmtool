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
    fputcsv($output, array('No', 'Project Name', 'Employee Name', 'Date', 'Title', 'Description', 'Priority', 'Status', 'Total Time', 'Break Time', 'Productive Time', 'Manager Status', 'Feedback'));

    // SQL query
    $sql = "SELECT task.tid, DATE(task.created_at) as created_date, projects.project_name, task.title, task.status, 
     task.estimated_time, task.priority, task.description, task.m_status, task.feedback, employees.fname, employees.lname, task_time.total_time, break.total_break
     FROM task 
     LEFT JOIN employees ON task.eid = employees.eid
     INNER JOIN projects ON task.pid = projects.pid
     INNER JOIN task_time ON task.tid = task_time.tid
     LEFT JOIN (SELECT tid, eid, pid, SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS total_break FROM 
     time_difference GROUP BY tid) AS break ON task.tid = break.tid AND task.eid = break.eid";

    // Initialize where conditions array
    $where_conditions = [];

     // Append filters to the query if a specific project is selected
    if ($project_id !== 'All') {
    $where_conditions[] = "task.pid = '$project_id'";
    }

   if ($employee_id !== 'All') {
    $where_conditions[] = "task.eid = '$employee_id'";
   }
    // Add conditions based on the selected time status
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

    // If there are any conditions, combine and append to the SQL query.
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    $sql .= " ORDER BY task.created_at DESC";

    // Execute the query
    $result = mysqli_query($db, $sql);

    // Check for errors
    if (!$result) {
        die('Error: ' . mysqli_error($db));
    }

    // Helper function to clean HTML content
    function RemoveHTMLTags($html) {
        // Decode HTML entities
        $html = html_entity_decode($html);

        // Replace non-breaking space, &amp;, - with a regular space, & and - 
        $html = str_replace(['&nbsp;', '&amp;', '-'], [' ', '&', ''], $html);

        // Strip the HTML tags
        return strip_tags($html);
    }

    // Fetch and write data to CSV
    if (mysqli_num_rows($result) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            // Clean and decode feedback and description
            $decode_feedback = RemoveHTMLTags($row["feedback"]);
            $decode_desc = RemoveHTMLTags($row["description"]);

            // Calculate total productive time
            list($total_hours, $total_minutes, $total_seconds) = explode(':', $row['total_time']);
            list($break_hours, $break_minutes, $break_seconds) = explode(':', $row['total_break']);

            $total_time_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;
            $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;
            $total_timeframe = $total_time_seconds - $total_break_time_seconds;

            $hours = floor($total_timeframe / 3600);
            $minutes = floor(($total_timeframe % 3600) / 60);
            $seconds = $total_timeframe % 60;
            $productive_task_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            $data = array(
                $i++,
                $row['project_name'],
                $row['fname'] . " " . $row['lname'],
                htmlspecialchars($row["created_date"]),
                $row['title'],
                $decode_desc,
                $row['priority'],
                $row['status'],
                $row['total_time'],
                substr($row['total_break'], 0, 8),
                $productive_task_time,
                $row['m_status'],
                $decode_feedback
            );
            fputcsv($output, $data);
        }
    } else {
        echo "No records found for the specified criteria.";
    }

    // Close output stream
    fclose($output);

    // Exit script
    exit;
}
?>
