<?php
require("db.php");


if (isset($_POST['submit'])) {
    
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {  // Check if a file was uploaded or not

        if ($db->connect_error) // $db is the Database connection object
        {  
            die("Connection failed: " . $db->connect_error);
        }

        $file = fopen($_FILES['file']['tmp_name'], 'r');  //To Open the uploaded CSV file

        fgetcsv($file);// Skip the first line (header)

        // Prepare the SQL statement with column names in database table
        $stmt = $db->prepare("INSERT INTO clients (client_name, business_name, industry, email_id, contact_number,category, services_looking, channel, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?)");

        // Process the CSV file line by line
        while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {
            // Convert contact_number column to string to prevent scientific notation
            $row[5] = sprintf('%s', $row[5]);// Assuming contact_number is at index 4 (zero-indexed)
            // Bind the CSV data to the prepared statement
            $stmt->bind_param(
                "ssssssssss",
                $row[0],  // client_name
                $row[1],  // business_name
                $row[2],  // industry
                $row[3],  // email_id
                $row[4],  // contact_number (converted to string)
                $row[5],  // category
                $row[6],  // services_looking
                $row[7],  // channel
                $row[8],  // status
                $row[9]   // notes
            );
            // Execute the prepared statement
            $stmt->execute();
        }

        // Close the file and statement
        fclose($file);
        $stmt->close();

        // Redirect to another file after successful import
        header("Location: http://localhost/ddspmtool/Dashboard/sales/clients.php?import_success=1");
        //header("Location: http://dds.doodlo.in/Dashboard/sales/clients.php?import_success=1");
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
