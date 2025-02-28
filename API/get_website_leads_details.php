<?php

require('website_leads_generation_api.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Sanitize the id to prevent SQL injection
    $id = mysqli_real_escape_string($db, $id);

    $sql = "SELECT * FROM `contact_form` WHERE `id` = '$id'";
    $query = mysqli_query($db, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $created_date = new DateTime($row["created_at"]);
        $date = $created_date->format('d M Y h:i:s A');
        $name = htmlspecialchars($row["name"]);
        $email = htmlspecialchars($row["email"]);
        $message = htmlspecialchars($row["message"]);
        $code = htmlspecialchars($row["code"]);
        $phone = htmlspecialchars($row["phone"]);
        $services = htmlspecialchars($row["services"]);
        $id_value = htmlspecialchars($row["id"]);

        // Decodes HTML entities back into their respective characters.
        // $removehtmltags = strip_tags($row["notes"]);
        // $decode_notes = html_entity_decode($removehtmltags);
        $servicesArray = json_decode($row["services"], true);

            // Join the array elements into a string separated by commas
            $servicesString = implode(", ", $servicesArray);
            

        echo '
            <div class=" ">
                <div class=" row "> 
                <hr>
                    <div class="col-lg-4"> 
                        <p> <b> Date </b> </p> 
                        <p>'.$date.'</span></p>
                    </div>
                      <div class="col-lg-4"> 
                        <p> <b>Name </b> </p> 
                        <p>'.$name.'</span></p>
                    </div>
                      <div class="col-lg-4"> 
                        <p> <b> Email </b> </p> 
                        <p>'.$email.'</span></p>
                    </div>
                    <hr>
                    
                      <div class="col-lg-4"> 
                        <p> <b> Code </b> </p> 
                        <p>'.$code.'</span></p>
                    </div>
                      <div class="col-lg-4"> 
                        <p> <b> Phone </b> </p> 
                        <p>'.$phone.'</span></p>
                    </div>
                     <hr>
                      <div class="col-lg-4"> 
                        <p><b>Services</b> </p> 
                        <p>'.$servicesString.'</span></p>
                    </div>
                    <hr>
                     <div class="col-lg-12"> 
                        <p> <b> Message </b> </p> 
                        <p>'.$message.'</span></p>
                    </div>

                </div>
            </div>
        ';
    } else {
        echo "No records found for id: $id";
    }
} else {
    echo "No id provided";
}
?>
