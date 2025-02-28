<?php 
// session_start();
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $operation = $_POST['ops'];

    // switch ($operation) {
    //     case "sales_lead_generation":  
    //         $client_name = $_POST['client_name'];
    //         $business_name = $_POST['business_name'];
    //         $industry = $_POST['industry'];
    //         $email_id = $_POST['email_id'];
    //         $contact_number = $_POST['contact_number'];
    //         $category = $_POST['category'];
    //         $services_looking = $_POST['services_looking'];
    //         $channel = $_POST['channel'];
    //         $status = $_POST['status'];
    //         $notes = $_POST['notes'];

    //         $stmt = $db->prepare("INSERT INTO sales_lead_generation (client_name, business_name, industry, email_id, contact_number,category, services_looking, channel, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    //         $stmt->bind_param("ssssssssss", $client_name, $business_name, $industry, $email_id, $contact_number,$category, $services_looking, $channel, $status, $notes);

    //         if ($stmt->execute()) {
    //             echo "Lead Added successfully";
    //         } else {
    //             echo "Error: " . $stmt->error;
    //         }
    //         $stmt->close();
    //         break;
    //     default:
    //         echo "Bad Gateway";
    // }


        //  new code by shraddha
        switch ($operation) {
            case "sales_lead_generation":
                $client_name = $_POST['client_name'];
                $business_name = $_POST['business_name'];
                $industry = $_POST['industry'];
                $email_id = $_POST['email_id'];
                $contact_number = $_POST['contact_number'];
                $category = $_POST['category'];
                $services_looking = $_POST['services_looking'];
                $channel = $_POST['channel'];
                $status = $_POST['status'];
                $notes = $_POST['notes'];
        
                if ($status == 'On Boarded') {
                    // Insert into clients table
                    $stmt = $db->prepare("INSERT INTO clients (client_name, business_name, industry, email_id, contact_number, category, services_looking, channel, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    if ($stmt === false) {
                        echo "Error: " . $db->error;
                        break;
                    }
                    $stmt->bind_param("ssssssssss", $client_name, $business_name, $industry, $email_id, $contact_number, $category, $services_looking, $channel, $status, $notes);
                    if ($stmt->execute()) {
                        echo "Client data added successfully. ";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                    $stmt->close();
                }
        
                // Insert into sales_lead_generation table
                $stmt = $db->prepare("INSERT INTO sales_lead_generation (client_name, business_name, industry, email_id, contact_number, category, services_looking, channel, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt === false) {
                    echo "Error: " . $db->error;
                    break;
                }
                $stmt->bind_param("ssssssssss", $client_name, $business_name, $industry, $email_id, $contact_number, $category, $services_looking, $channel, $status, $notes);
                if ($stmt->execute()) {
                    echo "Lead added successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
        
                // Close the database connection
                $db->close();
                break;
        
            default:
                echo "Bad Gateway";
        }
        
       

        // end of new code by shraddha

} else {
    echo "";
}

function get_leads($base_url, $db, $page=1, $recordsPerPage, $start_date = null, $end_date = null) {

    $recordsPerPage = $recordsPerPage > 1 ? $recordsPerPage : 10;
    $offset = ($page - 1) * $recordsPerPage;
    $time_status = isset($_GET['time_status']) ? $_GET['time_status'] : '';

    /************************************ get search data from user*****************************************/
    $search_data = isset($_GET['search_data']) ? $_GET['search_data'] : '';

    $client_status = isset($_GET['client_status']) ? $_GET['client_status'] : '';




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

/*********************** Query to search data by name ,email,mobile number  *************************/
    if (!empty($search_data)) {
        $search_data = mysqli_real_escape_string($db, $search_data); // Prevent SQL injection
        $where_conditions[] = "(client_name LIKE '%$search_data%' 
                               OR email_id LIKE '%$search_data%' 
                               OR business_name LIKE '%$search_data%'
                               OR contact_number LIKE '%$search_data%')";
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

    $sql .= " ORDER BY lead_id DESC LIMIT $offset, $recordsPerPage";

    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        $i = ($page - 1) * $recordsPerPage + 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            //$created_date = date("Y-m-d", strtotime($row["created_at"]));
             // Correct Date Formatting
            $date = new DateTime($row["created_at"]);
            $created_date = $date->format('d M Y h:i:s A');
            echo '<td>'.$created_date .'</td>';
            echo '<td>'. $row["client_name"].'</td>';
            echo '<td>'. $row["business_name"].'</td>';
            echo '<td>'. $row["email_id"].'</td>';
            echo '<td>'. $row["contact_number"].'</td>';
            echo '<td>'. $row["services_looking"].'</td>';
            echo '<td>'. $row["status"].'</td>';
            echo '<td>
                    <a href="../../Dashboard/sales/view_leads_details.php?lead_id='. $row["lead_id"].'"><i class=" bi bi-pencil-square "></i></a>
                    <a href="javascript:void(0);" onclick="openLeadDrawer('.$row["lead_id"].')"><i class="bi bi-eye"></i></a>
                    <i onclick="deleteLead('. $row["lead_id"].')" class=" text-danger bi bi-trash3"></i>
                </td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='9'>No results found.</td></tr>";
    }
}
?>
