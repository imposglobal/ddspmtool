<?php
require("db.php"); //db.php contains the database connection


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['ops'])) {
        $operation = $_POST['ops'];

            // new code 

            switch ($operation) {
                // Update Lead
                case "updateLead":
                    if (isset($_POST['lead_id'], $_POST['client_name'], $_POST['business_name'], $_POST['industry'], $_POST['email_id'], $_POST['contact_number'], $_POST['category'], $_POST['services_looking'], $_POST['channel'], $_POST['status'], $_POST['notes'])) {
                        $lead_id = $_POST['lead_id'];
                        $client_name = $_POST['client_name'];
                        $business_name = $_POST['business_name'];
                        $industry = $_POST['industry'];
                        $email_id = $_POST['email_id'];
                        $contact_number = $_POST['contact_number'];
                        $category = $_POST['category'];
                        $services_looking = $_POST['services_looking'];
                        $channel = $_POST['channel'];
                        $status = $_POST['status'];
                        $notes = $_POST['notes'];
            
                        // If status is 'On Boarded', insert data into the clients table only if it doesn't already exist
                        if ($status == 'On Boarded') {
                            // Check if the client already exists in the clients table
                            $checkClientSql = "SELECT * FROM clients WHERE lead_id = '$lead_id'";
                            $checkClientResult = $db->query($checkClientSql);
            
                            if ($checkClientResult->num_rows == 0) {
                                // Client does not exist, so insert
                                $addClientSql = "INSERT INTO clients (lead_id, client_name, business_name, industry, email_id, contact_number, category, services_looking, channel, status, notes) 
                                                 VALUES ('$lead_id', '$client_name', '$business_name', '$industry', '$email_id', '$contact_number', '$category', '$services_looking', '$channel', '$status', '$notes')";
            
                                if ($db->query($addClientSql) !== TRUE) {
                                    // Error inserting into clients table
                                    echo json_encode(array("success" => false, "message" => "Error inserting into clients: " . $db->error));
                                    exit; // Stop execution if there's an error
                                }
                            }
                        }
            
                        // Update sales_lead_generation table
                        $updateLeadSql = "UPDATE sales_lead_generation SET 
                            client_name = '$client_name', 
                            business_name = '$business_name', 
                            industry = '$industry', 
                            email_id = '$email_id', 
                            contact_number = '$contact_number', 
                            category = '$category', 
                            services_looking = '$services_looking', 
                            channel = '$channel', 
                            status = '$status', 
                            notes = '$notes' 
                            WHERE lead_id = '$lead_id'";
            
                        // Update clients table as well
                        $updateClientSql = "UPDATE clients SET 
                            client_name = '$client_name', 
                            business_name = '$business_name', 
                            industry = '$industry', 
                            email_id = '$email_id', 
                            contact_number = '$contact_number', 
                            category = '$category', 
                            services_looking = '$services_looking', 
                            channel = '$channel', 
                            status = '$status', 
                            notes = '$notes' 
                            WHERE lead_id = '$lead_id'";
            
                        // Execute both queries
                        $updateLeadResult = $db->query($updateLeadSql);
                        $updateClientResult = $db->query($updateClientSql);
            
                        if ($updateLeadResult === TRUE && $updateClientResult === TRUE) {
                            echo json_encode(array("success" => true, "message" => "updated successfully"));
                        } else if ($updateLeadResult === TRUE && $updateClientResult === FALSE) {
                            echo json_encode(array("success" => true, "message" => "Lead updated successfully"));
                        } else {
                            // Error updating sales_lead_generation table
                            echo json_encode(array("success" => false, "message" => "Error updating lead: " . $db->error));
                        }
                    } else {
                        echo json_encode(array("success" => false, "message" => "Incomplete data for updating lead"));
                    }
                    break;
            
                default:
                    echo json_encode(array("success" => false, "message" => "Invalid operation"));
                    break;
            }
            
            
            
            // new code

            }
        }
?>
