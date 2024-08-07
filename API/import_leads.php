<?php
require("db.php");


/***********************************************************************************/

// if (isset($_POST['submit'])) {
//     // Check if a file was uploaded
//     if (is_uploaded_file($_FILES['file']['tmp_name'])) {
//         // Open the uploaded CSV file
//         $file = fopen($_FILES['file']['tmp_name'], 'r');

//         // Skip the first line (header)
//         fgetcsv($file);

        

//         if ($db->connect_error) {
//             die("Connection failed: " . $db->connect_error);
//         }

//         // Prepare the SQL statement with placeholders
//         $stmt = $db->prepare("INSERT INTO sales_lead_generation (client_name, business_name, industry, email_id, contact_number, services_looking, channel, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

//         // Process the CSV file line by line
//         while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {
//             // Bind the CSV data to the prepared statement
//             $stmt->bind_param(
//                 "sssssssss",
//                 $row[0],  // client_name
//                 $row[1],  // business_name
//                 $row[2],  // industry
//                 $row[3],  // email_id
//                 $row[4],  // contact_number
//                 $row[5],  // services_looking
//                 $row[6],  // channel
//                 $row[7],  // status
//                 $row[8]   // notes
//             );

//             // Execute the prepared statement
//             $stmt->execute();
//         }

//         // Close the file and statement
//         fclose($file);
//         $stmt->close();

//         // Redirect to another file after successful import
//         header("Location: http://localhost/ddspmtool/Dashboard/sales/view_leads.php?import_success=1");
//         exit;  // Ensure script stops executing after redirection
//     } else {
//         echo "Error: Please upload a valid CSV file.";
//     }
// } else {
//     echo "Error: No file uploaded.";
// }


/************* */

if (isset($_POST['submit'])) {
    // Check if a file was uploaded
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        // Database connection assuming $db is your database connection object

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        // Open the uploaded CSV file
        $file = fopen($_FILES['file']['tmp_name'], 'r');

        // Skip the first line (header)
        fgetcsv($file);

        // Prepare the SQL statement with placeholders
        $stmt = $db->prepare("INSERT INTO sales_lead_generation (client_name, business_name, industry, email_id, contact_number, services_looking, channel, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Process the CSV file line by line
        while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {
            // Convert contact_number column to string to prevent scientific notation
            $row[5] = sprintf('%s', $row[5]);// Assuming contact_number is at index 4 (zero-indexed)
            // Bind the CSV data to the prepared statement
            $stmt->bind_param(
                "sssssssss",
                $row[0],  // client_name
                $row[1],  // business_name
                $row[2],  // industry
                $row[3],  // email_id
                $row[4],  // contact_number (converted to string)
                $row[5],  // services_looking
                $row[6],  // channel
                $row[7],  // status
                $row[8]   // notes
            );

            // Execute the prepared statement
            $stmt->execute();
        }

        // Close the file and statement
        fclose($file);
        $stmt->close();

        // Redirect to another file after successful import
        //header("Location: http://localhost/ddspmtool/Dashboard/sales/view_leads.php?import_success=1");
        header("Location: http://dds.doodlo.in/Dashboard/sales/view_leads.php?import_success=1");
        exit;  // Ensure script stops executing after redirection

        // header("Location: " . $_SERVER['PHP_SELF'] . "?import_success=1");
        // exit; 
    } else {
        echo "Error: Please upload a valid CSV file.";
    }
} else {
    echo "Error: No file uploaded.";
}

?>
