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


?>