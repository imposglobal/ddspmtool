<?php
require("db.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ops'])) {
       $variable = $_POST['ops'];

        switch ($variable) {
            case 'deleteUser':
                if(isset($_POST['eid'])) {
                    $eid = $_POST['eid'];
                    $sql = "DELETE FROM employees WHERE eid = '$eid' ";
                    if ($db->query($sql) === TRUE) 
                    {
                        
                        echo "true";
                    } else {
                        // If an error occurred, send error response
                        echo json_encode(array("success" => false, "message" => "Error during deletion: " . $db->error));
                    }
                } else {
                    echo "Invalid operation";
                }

                break;    
        
        }
    }
}



?>