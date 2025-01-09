<?php 
require("db.php");



function get_leads($base_url, $db, $page=1, $recordsPerPage, $start_date = null, $end_date = null) {

    $recordsPerPage = $recordsPerPage > 1 ? $recordsPerPage : 10;
    $offset = ($page - 1) * $recordsPerPage;
    $time_status = isset($_GET['time_status']) ? $_GET['time_status'] : '';

    /************************************ get search data from user*****************************************/
    $search_data = isset($_GET['search_data']) ? $_GET['search_data'] : '';

    $client_status = isset($_GET['client_status']) ? $_GET['client_status'] : '';




    $sql = "SELECT * FROM contact_form WHERE 1=1";
    $where_conditions = [];

    if (!empty($start_date)) {
        $where_conditions[] = "created_at >= '$start_date'";
    }
    if (!empty($end_date)) {
        $where_conditions[] = "created_at <= '$end_date'";
    }

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

/*********************** Query to search data by name ,email,mobile number  *************************/
    if (!empty($search_data)) {
        $search_data = mysqli_real_escape_string($db, $search_data); // Prevent SQL injection
        $where_conditions[] = "(name LIKE '%$search_data%' 
                               OR email LIKE '%$search_data%' 
                               OR code LIKE '%$search_data%'
                               OR phone LIKE '%$search_data%'
                               OR services LIKE '%$search_data%')";
    }
/*********************** Query to search data by status  *************************/

    if (!empty($client_status)) {
        $client_status = mysqli_real_escape_string($db, $client_status); // Prevent SQL injection
        // $where_conditions[] = "(status LIKE '%$client_status%')";
        $where_conditions[] = "status = '$client_status'";
    }

    if (!empty($where_conditions)) {
        $sql .= " AND " . implode(" AND ", $where_conditions);
    }

    $sql .= " ORDER BY id DESC LIMIT $offset, $recordsPerPage";

    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        $i = ($page - 1) * $recordsPerPage + 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            $created_date = date("Y-m-d", strtotime($row["created_at"]));
            echo '<td>'.$created_date .'</td>';
            echo '<td>'. $row["name"].'</td>';
            echo '<td>'. $row["email"].'</td>';
            echo '<td>' . $row["code"] . ' ' . $row["phone"] . '</td>';
            
            $servicesArray = json_decode($row["services"], true);

            // Join the array elements into a string separated by commas
            $servicesString = implode(", ", $servicesArray);
            echo '<td>' . $servicesString . '</td>';

            
            echo '<td>
                    <a href="javascript:void(0);" onclick="openLeadDrawer('.$row["id"].')"><i class="bi bi-eye"></i></a>
                    <i onclick="deleteLead('. $row["id"].')" class=" text-danger bi bi-trash3"></i>
                </td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='9'>No results found.</td></tr>";
    }
}
?>
