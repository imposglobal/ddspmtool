<?php 

require("db.php");

/********************************** code for add notes ********************************************************* */

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $operation = $_POST['ops'];

//     switch ($operation) {
//         case "add_notes":
//             // Validate and sanitize input (you can use more robust validation as needed)
//             $notes = isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '';
//              $eid = isset($_POST['eid']) ? intval($_POST['eid']) : 0;
//              $lead_id = isset($_POST['lead_id']) ? intval($_POST['lead_id']) : '';

//             if ($notes === '' || $eid <= 0 || $lead_id <= 0) {
//                 echo "Error: Invalid input data";
//                 exit;
//             }

//             // Prepare and bind
//             $stmt = $db->prepare("INSERT INTO sales_notes (notes,eid,lead_id) VALUES (?,?,?)");
//             $stmt->bind_param("sii", $notes,$eid,$lead_id); // 's' for string, 'i' for integer

//             // Execute the statement and check for errors
//             if ($stmt->execute()) {
//                 echo " ";
//             } else {
//                 echo "Error: " . $stmt->error;
//             }

//             // Close the statement
//             $stmt->close();
//             break;

//         default:
//             echo "Bad Gateway";
//             break;
//     }
// } else {
//     echo "Method not allowed";
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $operation = $_POST['ops'];

    switch ($operation) {
        case "add_notes":
            // Validate and sanitize input (you can use more robust validation as needed)
            $notes = isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '';
            $eid = isset($_POST['eid']) ? intval($_POST['eid']) : 0;
            $lead_id = isset($_POST['lead_id']) ? intval($_POST['lead_id']) : 0;

            // Debugging: Output the received input
            error_log("Received input - notes: $notes, eid: $eid, lead_id: $lead_id");

            if ($notes === '' || $eid <= 0 || $lead_id <= 0) {
                echo "Error: Invalid input data";
                exit;
            }

            // Prepare and bind
            $stmt = $db->prepare("INSERT INTO sales_notes (notes, eid, lead_id) VALUES (?, ?, ?)");
            if ($stmt === false) {
                // Debugging: Check if the prepared statement failed
                error_log("Prepare statement failed: " . $db->error);
                echo "Error: " . $db->error;
                exit;
            }

            // Debugging: Output the query being executed
            error_log("Executing query: INSERT INTO sales_notes (notes, eid, lead_id) VALUES ('$notes', $eid, $lead_id)");

            $stmt->bind_param("sii", $notes, $eid, $lead_id); // 's' for string, 'i' for integer

            // Execute the statement and check for errors
            if ($stmt->execute()) {
                echo " ";
            } else {
                // Debugging: Output the statement error
                error_log("Statement execute failed: " . $stmt->error);
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
            break;

        default:
            echo "Bad Gateway";
            break;
    }
} else {
    echo "Method not allowed";
}



/**************************************** get notes function ***************************************************/
function get_notes($base_url, $db, $lead_id) {
    // Ensure lead_id is an integer to prevent SQL injection
    $lead_id = intval($lead_id);

    // SQL query to fetch all notes with employee details for a specific lead_id
    $sql = "SELECT 
                sales_notes.notes_id, 
                sales_notes.notes, 
                employees.fname, 
                employees.lname, 
                sales_notes.created_at,
                sales_lead_generation.lead_id 
            FROM 
                sales_notes
            JOIN 
                employees ON sales_notes.eid = employees.eid
            JOIN 
                sales_lead_generation ON sales_notes.lead_id = sales_lead_generation.lead_id 
            WHERE 
                sales_notes.lead_id = $lead_id 
            ORDER BY 
                sales_notes.notes_id DESC";

    $result = mysqli_query($db, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Decode HTML entities and remove HTML tags from notes
            $notes = strip_tags(html_entity_decode($row["notes"]));
            // Remove specific HTML entities like &nbsp;
            $notes = preg_replace('/&nbsp;/', ' ', $notes);
            $notes = preg_replace('/\s+/', ' ', $notes); // Remove multiple spaces
            
            $created_date = date("Y-m-d", strtotime($row["created_at"]));
            echo '
                  <br/>
                <div style="text-align: justify; font-size: medium;">
                    <p>' . htmlspecialchars($notes) . '</p>
                    
                </div>
                <div style="text-align: justify; font-size: small; ">
                    <p>By: ' . htmlspecialchars($row["fname"]) . ' ' . htmlspecialchars($row["lname"]) . ' ( '.$row["created_at"] .' )</p>
                </div>
                
                <hr>
            ';
        }
    } else {
        echo "<div></div>";
    }

    // Free result set
    mysqli_free_result($result);
}








?>
