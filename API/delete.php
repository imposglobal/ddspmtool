<?php
require("db.php");

// Delete Employees

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

// Delete Projects

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ops'])) {
       $variable = $_POST['ops'];

        switch ($variable) {
            case 'deleteProject':
                if(isset($_POST['pid'])) {
                    $pid = $_POST['pid'];
                    $project_name = $_POST['project_name'];
                  
                    $sql = "DELETE FROM projects WHERE pid = '$pid' ";
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

// delete task


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ops'])) {
       $variable = $_POST['ops'];

        switch ($variable) {
            case 'deleteTask':
                if(isset($_POST['tid'])) 
                {
                    $tid = $_POST['tid'];              
                    $sql = "DELETE FROM task WHERE tid = '$tid' ";
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


// delete client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ops'])) {
       $variable = $_POST['ops'];

        switch ($variable) {
            case 'deleteClient':
                if(isset($_POST['cid'])) 
                {
                    $cid = $_POST['cid'];              
                    $sql = "DELETE FROM clients WHERE cid = '$cid' ";
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