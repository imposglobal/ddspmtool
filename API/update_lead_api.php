<?php
require("db.php"); //db.php contains the database connection


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ops'])) {
        $operation = $_POST['ops'];

        switch ($operation) {
        
            // Update Lead
                 case "updateLead":
                    
                    if(isset($_POST['lead_id'], $_POST['date'], $_POST['client_name'], $_POST['business_name'], $_POST['industry'], $_POST['email_id'], $_POST['contact_number'], $_POST['services_looking'], $_POST['channel'], $_POST['status'], $_POST['notes'])) {
                        $lead_id = $_POST['lead_id'];
                        $date = $_POST['date'];
                        $client_name = $_POST['client_name'];
                        $business_name = $_POST['business_name'];
                        $industry = $_POST['industry'];
                        $email_id = $_POST['email_id'];
                        $contact_number = $_POST['contact_number'];
                        $services_looking = $_POST['services_looking'];
                        $channel = $_POST['channel'];
                        $status = $_POST['status'];
                        $notes = $_POST['notes'];
                       
                        
                        
                        $sql = "UPDATE sales_lead_generation SET date = '$date', client_name = '$client_name', business_name = '$business_name', industry = '$industry', email_id = '$email_id', contact_number = '$contact_number', services_looking = '$services_looking', channel = '$channel', status = '$status', notes = '$notes' WHERE lead_id = '$lead_id' ";
                        
                        if ($db->query($sql) === TRUE) 
                        {
                            // Return success message as JSON
                            echo json_encode(array("success" => true, "message" => "Lead updated successfully"));
                        } else {
                            // If an error occurred, send error response
                            echo json_encode(array("success" => false, "message" => "Error updating profile: " . $db->error));
                        }
                    } else {
                        echo "Incomplete data for updating lead";
                    }
                    break;
              
                }
            }
        }
?>
