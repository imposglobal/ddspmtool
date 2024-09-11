<?php
require("db.php");


// Delete Leads

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ops'])) {
       $variable = $_POST['ops'];

        switch ($variable) {
            case 'deleteLead':
                if(isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $sql = "DELETE FROM contact_form WHERE id = '$id' ";
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