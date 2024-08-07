<?php
require("db.php");

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Retrieve the search term
    $searchTerm = $_POST['search'];
    echo $searchTerm;

    // Escape the search term to prevent SQL injection
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

    // Perform your search query
    $query = "SELECT * FROM sales_lead_generation WHERE client_name LIKE '%$searchTerm%'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Display search results
        while ($row = mysqli_fetch_assoc($result)) {
            // Example: Display the results
            echo "<p>" . $row['client_name'] . "</p>";
        }
    } else {
        echo "No results found.";
    }
}
?>
