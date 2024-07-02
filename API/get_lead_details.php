<?php

require('sales_lead_generation_api.php');

if (isset($_GET['lead_id'])) {
    $lead_id = $_GET['lead_id'];
    // Sanitize the lead_id to prevent SQL injection
    $lead_id = mysqli_real_escape_string($db, $lead_id);

    $sql = "SELECT * FROM `sales_lead_generation` WHERE `lead_id` = '$lead_id'";
    $query = mysqli_query($db, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        
        $created_date = date("Y-m-d", strtotime($row["created_at"]));
        $date = htmlspecialchars($created_date);
        $client_name = htmlspecialchars($row["client_name"]);
        $business_name = htmlspecialchars($row["business_name"]);
        $industry = htmlspecialchars($row["industry"]);
        $email_id = htmlspecialchars($row["email_id"]);
        $contact_number = htmlspecialchars($row["contact_number"]);
        $services_looking = htmlspecialchars($row["services_looking"]);
        $channel = htmlspecialchars($row["channel"]);
        $status = htmlspecialchars($row["status"]);
        $lead_id_value = htmlspecialchars($row["lead_id"]);

        // Decodes HTML entities back into their respective characters.
        $removehtmltags = strip_tags($row["notes"]);
        $decode_notes = html_entity_decode($removehtmltags);

        echo '
            <div class=" ">
                <div class=" row "> 
                <hr>
                    <div class="col-lg-4"> 
                        <p> <b> Date </b> </p> 
                        <p>'.$date.'</span></p>
                    </div>
                      <div class="col-lg-4"> 
                        <p> <b> Client Name </b> </p> 
                        <p>'.$client_name.'</span></p>
                    </div>
                      <div class="col-lg-4"> 
                        <p> <b> Business Name </b> </p> 
                        <p>'.$business_name.'</span></p>
                    </div>
                    <hr>
                     <div class="col-lg-4"> 
                        <p> <b> Industry </b> </p> 
                        <p>'.$industry.'</span></p>
                    </div>
                      <div class="col-lg-4"> 
                        <p> <b> Email </b> </p> 
                        <p>'.$email_id.'</span></p>
                    </div>
                      <div class="col-lg-4"> 
                        <p> <b> Contact number </b> </p> 
                        <p>'.$contact_number.'</span></p>
                    </div>
                     <hr>
                     <div class="col-lg-4"> 
                        <p><b>Looking For?</b> </p> 
                        <p>'.$services_looking.'</span></p>
                    </div>
                      <div class="col-lg-4"> 
                        <p> <b> Channel </b></p> 
                        <p>'.$channel.'</span></p>
                    </div>
                    <div class="col-lg-4"> 
                        <p><b> Status </b> </p> 
                        <p>'.$status.'</span></p>
                    </div>
                    <hr>
                    <div class="col-lg-4"> 
                        <p><b> Notes </b> </p> 
                        <p>'.$decode_notes.'</span></p>
                    </div>

                </div>
            </div>
        ';
    } else {
        echo "No records found for lead_id: $lead_id";
    }
} else {
    echo "No lead_id provided";
}
?>
