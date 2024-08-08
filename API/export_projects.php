<?php
require("db.php");

 if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : 'All';
    $project_type = isset($_GET['project_type']) ? $_GET['project_type'] : 'All';
    $time_status = isset($_GET['time_status']) ? $_GET['time_status'] : '';
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Set headers for CSV export
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=employee_tasks.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen("php://output", "w");

    // Write headers to CSV
    fputcsv($output, array('No', 'Date', 'Client Name', 'Project Name', 'Project Type', 'Employee Name', 'Title', 'Description', 'Priority', 'Status', 'Total Time', 'Break Time', 'Productive Time',  'Manager Status', 'Feedback'));

    $sql = "SELECT task.tid, DATE(task.created_at) as created_date, projects.project_name, projects.cid, clients.business_name, task.project_type, task.title, task.status, 
    task.estimated_time, task.priority, task.description, task.m_status, task.feedback, 
    employees.fname, employees.lname, task_time.total_time
    FROM task 
    INNER JOIN projects ON task.pid = projects.pid
    LEFT JOIN clients ON clients.cid = projects.cid
    LEFT JOIN employees ON task.eid = employees.eid
    INNER JOIN task_time ON task.tid = task_time.tid";

    // Initialize where conditions array
    $where_conditions = [];

    // Append filters to the query if a specific project is selected
   // Append filters to the query if a specific client is selected
    if ($client_id !== 'All') {
    $where_conditions[] = "projects.cid = '$client_id'";
    }

    // Append filters to the query if a specific project is selected
    if ($project_type !== 'All') {
        $where_conditions[] = "task.project_type = '$project_type'";
    }

    // Append filters to the query
    if (!empty($start_date)) {
        $where_conditions[] = "task.created_at >= '$start_date'";
    }
    if (!empty($end_date)) {
        $where_conditions[] = "task.created_at <= '$end_date'";
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
            $where_conditions[] = "WEEK(task.created_at) = WEEK(CURRENT_DATE())";
            break;
        case 'monthly':
            $where_conditions[] = "MONTH(task.created_at) = MONTH(CURRENT_DATE())";
            break;
    }

    // Combine where conditions if any
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
        
        // Replace non-breaking space , &amp; , -  with a regular space , & and - 
        $html = str_replace(['&nbsp;', '&amp;', '-'], [' ', '&', ''], $html);

        // now Strip the HTML tags means it will remove all the html tags from input in html tags
        return strip_tags($html);
       
    }

    // Fetch and write data to CSV
    if (mysqli_num_rows($result) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $tid = $row["tid"];

            // Clean and decode feedback and description
            $decode_feedback = RemoveHTMLTags($row["feedback"]);
            $decode_desc = RemoveHTMLTags($row["description"]);

            // Get task break time
            $sql1 = "SELECT tid, eid, pid, SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS task_break 
                     FROM time_difference 
                     WHERE tid = $tid";
            $result1 = mysqli_query($db, $sql1);
            $row1 = mysqli_fetch_assoc($result1);

            $task_total_time = strtotime($row['total_time']);
            $task_break_time = strtotime($row1['task_break']);

            // Subtract total_break from total_time
            $actual_time = $task_total_time - $task_break_time;
            $actual_task_time = gmdate('H:i:s', $actual_time);

            $data = array(
                $i++,
                htmlspecialchars($row["created_date"]),
                htmlspecialchars($row['business_name']),
                htmlspecialchars($row['project_name']),
                htmlspecialchars($row['project_type']),
                htmlspecialchars($row['fname'] . " " . $row['lname']),               
                htmlspecialchars($row['title']),
                $decode_desc,
                htmlspecialchars($row['priority']),
                htmlspecialchars($row['status']),
                htmlspecialchars($row['total_time']),
                htmlspecialchars(substr($row1['task_break'], 0, 8)),
                htmlspecialchars($actual_task_time),               
                htmlspecialchars($row['m_status']),
                $decode_feedback
               
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
