<?php
require("db.php");

if (isset($_POST['submit'])) {
    $searchTerm = $_POST['search'];
    $searchTerm = mysqli_real_escape_string($db, $searchTerm);

    // Perform your search query
    $query = "
        SELECT * 
        FROM sales_lead_generation 
        WHERE client_name LIKE '%$searchTerm%' 
        OR email_id LIKE '%$searchTerm%' 
        OR contact_number LIKE '%$searchTerm%'
    ";
    $result = mysqli_query($db, $query);

    $results = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    } else {
        $results = [];
    }

    // Encode results to JSON format
    echo json_encode($results);
}


?>
