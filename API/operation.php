<?php
require("db.php");

// Function to view employees details
function get_employees($db, $page = 1, $recordsPerPage = 10){
   
    $offset = ($page - 1) * $recordsPerPage;   // Calculate offset
    $sql = "SELECT * FROM employees LIMIT $offset, $recordsPerPage";  // Fetch employees with pagination
    $result = mysqli_query($db, $sql);
    // print_r($result);
    
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>'. $row["fname"]." ". $row["lname"].'</td>';
            echo '<td>'. $row["email"].'</td>';
            echo '<td>'. $row["designation"].'</td>';
            echo '<td>'. $row["department"].'</td>';
            echo '<td><a href="project.php?empid='. $row["emp_id"].'"><i class="icon bi bi-pencil-square"></i></a> </i> <i class="icon text-danger bi bi-trash3"></i></td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
    
}

?>
