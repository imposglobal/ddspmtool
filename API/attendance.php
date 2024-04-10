<?php
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($operation) {
        //project Add
        case "project":
          $pname = $_POST['pname'];
          $desc = $_POST['description'];
          $created_at = date('y-m-d H:i:s');
    
          $sql = "INSERT INTO projects (project_name, description, created_at) VALUES ('$pname', '$desc', '$created_at')";
          if ($db->query($sql) === TRUE) {
            echo "Project Added successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $db->error;
            }
            // Close dbection
            $db->close();
    
          break;
    
        default:
          echo "Bad Gateway";
      }
    

}
