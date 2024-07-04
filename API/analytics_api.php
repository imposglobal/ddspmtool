<?php
require("db.php");

// code to retrive data from table projects
$query = "SELECT project_name, COUNT(*) AS count FROM projects GROUP BY project_name";
$result = mysqli_query($db,$query);
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['project_name']] = $row['count'];
}
// JSON encode the data for AJAX response
echo json_encode($data);



// function to get project count
function total_project_count($db)
{
  
    $sql = "SELECT COUNT(*) AS project_count FROM projects";
    // Execute the query using the provided database connection object
    $result = $db->query($sql);
    if ($result) {
        if ($result->num_rows > 0) {
            // Fetch the result
            $row = $result->fetch_assoc();
            $projectCount = $row["project_count"];

            // Return the project count
            return $projectCount;
        } else {
            return 0; // No projects found
        }
    } else {
        return -1; // Error executing query
    }
}
?>