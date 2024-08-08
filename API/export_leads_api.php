<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
    $time_status = isset($_GET['time_status']) ? $_GET['time_status'] : '';
    $client_status = isset($_GET['client_status']) ? $_GET['client_status'] : '';

    // Check if start_date and end_date are the same
    if ($start_date === $end_date) {
        $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
    }

    // Set headers for CSV export
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=sales_lead_generation.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Open output stream
    $output = fopen("php://output", "w");

    // Write headers to CSV
    fputcsv($output, array('Lead ID', 'Lead Date', 'Client Name', 'Business Name', 'Industry', 'Email ID', 'Contact Number','Category', 'Services Looking', 'Channel', 'Status', 'Notes'));

    // Construct the SQL query with filters
    $sql = "SELECT lead_id, created_at, client_name, business_name, industry, email_id, contact_number, category, services_looking, channel, status, notes 
            FROM sales_lead_generation";

    // Initialize where conditions array
    $where_conditions = [];

    // Append filters to the query
    if (!empty($start_date)) {
        $where_conditions[] = "created_at >= '$start_date'";
    }
    if (!empty($end_date)) {
        $where_conditions[] = "created_at <= '$end_date'";
    }

    // Add conditions based on the selected time status
    switch ($time_status) {
        case 'today':
            $where_conditions[] = "DATE(created_at) = CURRENT_DATE()";
            break;
        case 'yesterday':
            $where_conditions[] = "DATE(created_at) = CURRENT_DATE() - INTERVAL 1 DAY";
            break;
        case 'weekly':
            $where_conditions[] = "WEEK(created_at) = WEEK(CURRENT_DATE())";
            break;
        case 'monthly':
            $where_conditions[] = "MONTH(created_at) = MONTH(CURRENT_DATE())";
            break;
    }

    // Add client_status condition if it is not empty
    if (!empty($client_status)) {
        $where_conditions[] = "status = '$client_status'";
    }

    // Combine where conditions if any
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    $sql .= " ORDER BY created_at DESC";

    // Execute the query
    $result = mysqli_query($db, $sql);

    // Check for errors
    if (!$result) {
        die('Error: ' . mysqli_error($db));
    }

    // Helper function to clean HTML content
    function RemoveHTMLTags($html) {
        $html = html_entity_decode($html);
        $html = str_replace(['&nbsp;', '&amp;', '-'], [' ', '&', ''], $html);
        return strip_tags($html);
    }

    // Fetch and write data to CSV
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $decode_notes = RemoveHTMLTags($row["notes"]);

            $data = array(
                htmlspecialchars($row['lead_id']),
                htmlspecialchars($row['created_at']),
                htmlspecialchars($row['client_name']),
                htmlspecialchars($row['business_name']),
                htmlspecialchars($row['industry']),
                htmlspecialchars($row['email_id']),
                htmlspecialchars($row['contact_number']),
                htmlspecialchars($row['category']),
                htmlspecialchars($row['services_looking']),
                htmlspecialchars($row['channel']),
                htmlspecialchars($row['status']),
                $decode_notes
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
