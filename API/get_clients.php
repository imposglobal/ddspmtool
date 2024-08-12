<?php
session_start();
require("db.php");
require("function.php");


function get_clients($db, $page = 1, $recordsPerPage = 10){
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;

    // Filter parameters
    $start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($db, $_GET['start_date']) : '';
    $end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($db, $_GET['end_date']) : '';
    $time_status = isset($_GET['time_status']) ? mysqli_real_escape_string($db, $_GET['time_status']) : '';
    $search_data = isset($_GET['search_data']) ? $_GET['search_data'] : '';

    // Check if start_date and end_date are the same
    if ($start_date === $end_date) {
        $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
    }

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

    /*********************** Query to search data by client_name, buisness_name, industry*************/
    if (!empty($search_data)) {
        $search_data = mysqli_real_escape_string($db, $search_data); // Prevent SQL injection
        $where_conditions[] = "(client_name LIKE '%$search_data%' 
                               OR business_name LIKE '%$search_data%' 
                               OR industry LIKE '%$search_data%')";
    }

    // Start constructing the SQL query
    $sql = "SELECT *, DATE(created_at) as created_date FROM clients";

    // Combine where conditions if any
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    // Add order by and limit clauses
    $sql .= " ORDER BY created_date DESC LIMIT $offset, $recordsPerPage";

    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>'.htmlspecialchars($row["created_date"]).'</td>';
            echo '<td>'. htmlspecialchars($row["client_name"]).'</td>';
            echo '<td>'. htmlspecialchars($row["business_name"]).'</td>';
            echo '<td>'. htmlspecialchars($row["status"]).'</td>';
            echo '<td>
                   <a href="../../Dashboard/sales/view_leads_details.php?lead_id='. $row["lead_id"].'"><i class=" bi bi-pencil-square "></i></a>
                   <i onclick="deleteClient('. htmlspecialchars($row["cid"]).', \''. htmlspecialchars($row["business_name"]).'\')" class="icon text-danger bi bi-trash3"></i>
                  </td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
}




?>