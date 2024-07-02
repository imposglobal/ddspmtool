<?php 
session_start();
require("db.php");

// lead form submitting code

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$operation = $_POST['ops']; // fetch  data in ops from ajax code in add_leads.php


switch ($operation) {
   
      
    case "sales_lead_generation":  
  
        // $date = $_POST['date'];
        $client_name = $_POST['client_name'];
        $business_name = $_POST['business_name'];
        $industry = $_POST['industry'];
        $email_id = $_POST['email_id'];
        $contact_number = $_POST['contact_number'];
        $services_looking = $_POST['services_looking'];
        $channel = $_POST['channel'];
        $status = $_POST['status'];
        $notes = $_POST['notes'];


        
     // query to insert data into table sales_lead_generation
        $sql = "INSERT INTO sales_lead_generation ( client_name , business_name , industry , email_id , contact_number , services_looking , channel , status , notes) VALUES
                ('$client_name','$business_name','$industry','$email_id','$contact_number','$services_looking','$channel','$status','$notes')";
        
        
        if ($db->query($sql) === TRUE) {
            echo "Lead Added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $db->error;
        }
    break;

    default:
      echo "Bad Gateway";
  }


}else{
    echo"";
}



/************************************************ Function to get leads data *********************************************************88 */

function get_leads($base_url,$db, $page = 1, $recordsPerPage = 10){
   
    $offset = ($page - 1) * $recordsPerPage;   // Calculate offset
    //$sql = "SELECT * FROM sales_lead_generation LIMIT $offset, $recordsPerPage";  // Fetch employees with pagination
    $sql = "SELECT * FROM sales_lead_generation ORDER BY lead_id DESC LIMIT $offset, $recordsPerPage";

    $result = mysqli_query($db, $sql);
    // print_r($result);
    
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            $created_date = date("Y-m-d", strtotime($row["created_at"]));
             echo '<td>'.$created_date .'</td>';
           // echo '<td>'. $row["lead_id"].'</td>';
            echo '<td>'. $row["client_name"].'</td>';
            echo '<td>'. $row["business_name"].'</td>';
            // echo '<td>'. $row["industry"].'</td>';
            echo '<td>'. $row["email_id"].'</td>';
            echo '<td>'. $row["contact_number"].'</td>';
            echo '<td>'. $row["services_looking"].'</td>';
            // echo '<td>'. $row["channel"].'</td>';
            echo '<td>'. $row["status"].'</td>';
            

            echo '<td><a href="../../Dashboard/sales/view_leads_details.php?lead_id='. $row["lead_id"].'"><i class="icon bi bi-info-circle-fill "></i></a>       <a href="javascript:void(0);" onclick="openLeadDrawer('.$row["lead_id"].')"><i class="icon bi bi-info-circle-fill"></i></a>      <i onclick="deleteLead('. $row["lead_id"].')" class="icon text-danger bi bi-trash3"></i></td>';
           
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
    
}

/******************************************* Delete lead */




?>