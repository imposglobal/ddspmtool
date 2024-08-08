<?php
session_start();
require("db.php");
require("function.php");


function get_clients($db, $page = 1, $recordsPerPage = 10){
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;
    
    // Fetch projects with pagination
    $sql = "SELECT *, DATE(created_at) as created_date  FROM clients ORDER BY cid DESC LIMIT $offset, $recordsPerPage";
    $result = mysqli_query($db, $sql);
    
    if (mysqli_num_rows($result) > 0) 
    {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
    
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>'.htmlspecialchars($row["created_date"]).'</td>';
            echo '<td>'. $row["client_name"].'</td>';
            echo '<td>'. $row["business_name"].'</td>';
            echo '<td>'. $row["industry"].'</td>';
            echo '<td>'. $row["status"].'</td>';
            // echo '<td><i onclick="deleteClient('. $row["cid"].',\''. $row["business_name"].'\')" class="icon text-danger bi bi-trash3"></i></td>';

            echo '<td>
           <i onclick="deleteClient('. $row["cid"].',\''. $row["business_name"].'\')" class="icon text-danger bi bi-trash3"></i></td>';
            echo '</tr>';
        }
    } 
    else 
    {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
    
}



?>