<?php 
session_start();
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $operation = $_POST['ops'];

    switch ($operation) {
        case "sales_lead_generation":  
            $client_name = $_POST['client_name'];
            $business_name = $_POST['business_name'];
            $industry = $_POST['industry'];
            $email_id = $_POST['email_id'];
            $contact_number = $_POST['contact_number'];
            $services_looking = $_POST['services_looking'];
            $channel = $_POST['channel'];
            $status = $_POST['status'];
            $notes = $_POST['notes'];

            $stmt = $db->prepare("INSERT INTO sales_lead_generation (client_name, business_name, industry, email_id, contact_number, services_looking, channel, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $client_name, $business_name, $industry, $email_id, $contact_number, $services_looking, $channel, $status, $notes);

            if ($stmt->execute()) {
                echo "Lead Added successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
            break;

        default:
            echo "Bad Gateway";
    }
} else {
    echo "";
}

function get_leads($base_url, $db, $page = 1, $recordsPerPage = 10, $start_date = null, $end_date = null) {
    $offset = ($page - 1) * $recordsPerPage;
    $time_status = isset($_GET['time_status']) ? $_GET['time_status'] : '';

    $sql = "SELECT * FROM sales_lead_generation WHERE 1=1";
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

    if (!empty($where_conditions)) {
        $sql .= " AND " . implode(" AND ", $where_conditions);
    }

    $sql .= " ORDER BY lead_id DESC LIMIT $offset, $recordsPerPage";

    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        $i = ($page - 1) * $recordsPerPage + 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            $created_date = date("Y-m-d", strtotime($row["created_at"]));
            echo '<td>'.$created_date .'</td>';
            echo '<td>'. $row["client_name"].'</td>';
            echo '<td>'. $row["business_name"].'</td>';
            echo '<td>'. $row["email_id"].'</td>';
            echo '<td>'. $row["contact_number"].'</td>';
            echo '<td>'. $row["services_looking"].'</td>';
            echo '<td>'. $row["status"].'</td>';
            echo '<td>
                    <a href="../../Dashboard/sales/view_leads_details.php?lead_id='. $row["lead_id"].'"><i class="icon bi bi-info-circle-fill "></i></a>
                    <a href="javascript:void(0);" onclick="openLeadDrawer('.$row["lead_id"].')"><i class="icon bi bi-info-circle-fill"></i></a>
                    <i onclick="deleteLead('. $row["lead_id"].')" class="icon text-danger bi bi-trash3"></i>
                </td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='9'>No results found.</td></tr>";
    }
}
?>
