<?php 
require('../header.php');

?>
<title>View Leads Details - DDS</title>
<?php 
require('../sidebar.php');

require('../../API/sales_lead_generation_api.php');
require('../../API/add_lead_lotes_api.php');

?>
<style>
  #status > option:nth-child(2) {
  background-color: green;
  color: #fff;
}
#status > option:nth-child(3){
  background-color: #dec016;
  color: #fff;
}
#status > option:nth-child(4){
  background-color: #eb7e09;
  color: #fff;
}
#status > option:nth-child(5){
  background-color: #eb6709;
  color: #fff;
}
#status > option:nth-child(6){
  background-color: red;
  color: #fff;
}
.card-title {
    padding: 20px 0 0px 0;
    font-size: 16px;
    font-weight: 400;
    color: #012970;
    font-family: "Poppins", sans-serif;
}
.swal2-title {
    color: #012970;
    font-size: 20px;
    font-weight: 600;
    font-family: "Poppins", sans-serif;
}

.drop-area {
    border: 2px dashed #ccc;
    padding: 40px;
    text-align: center;
    margin-bottom: 20px;
}

</style>

<?php
if (isset($_GET['lead_id'])) {
    $lead_id = $_GET['lead_id'];
    
    // Sanitize the lead_id to prevent SQL injection
    $lead_id = mysqli_real_escape_string($db, $lead_id);

    // Update the SQL query to filter by lead_id
    $sql = "SELECT * FROM `sales_lead_generation` WHERE `lead_id` = '$lead_id'";
    $query = mysqli_query($db, $sql);
    
    // Check if the query returned any rows
    if ($query && mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            // For feedback field
            // Removes any HTML tags from it using the strip_tags()
            $removehtmltags = strip_tags($row["notes"]);
            // Decodes HTML entities back into their respective characters.
            $decode_notes = html_entity_decode($removehtmltags);

            // Output the data (for debugging purposes)
            $created_date = date("Y-m-d", strtotime($row["created_at"]));
            $date = htmlspecialchars($created_date);
            $client_name = htmlspecialchars($row["client_name"]);
            $business_name = htmlspecialchars($row["business_name"]);
            $industry = htmlspecialchars($row["industry"]);
            $email_id = htmlspecialchars($row["email_id"]);
            $contact_number = htmlspecialchars($row["contact_number"]);
            $category = htmlspecialchars($row["category"]);
            $services_looking = htmlspecialchars($row["services_looking"]);
            $channel = htmlspecialchars($row["channel"]);
            $status = htmlspecialchars($row["status"]);
            $lead_id_value = htmlspecialchars($row["lead_id"]);
        }
    } else {
        echo "No records found for lead_id: $lead_id";
    }
} else {
    echo "No lead_id provided";
}
?>

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Lead Update</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">Lead Update</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="row">   
                <div class="col-lg-12">
                    <div class="card-body">
                        <h5 class="card-title pb-1 pt-4">Lead Update</h5>
                        <hr>
                        <form method="POST">
                        <div class="row mt-5">
                            <div class="col-lg-">
                                <div class="row">
                                    <div class=" col-md-4"> 
                                        <label class="ctitle mb-3">Date</label>
                                        <input type="date" id="date" value="<?php echo $date;?>" class="form-control"> <br>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Client Name</label>
                                        <input type="text" id="client_name" value="<?php echo $client_name;?>" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Business Name</label>
                                        <input type="text" id="business_name" value="<?php echo $business_name;?>" class="form-control"> <br><br>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Industry</label>
                                        <select id="industry" name="industry" class="form-control">
                                            <option value="Banking Financial Services" <?php echo ($industry == 'Banking Financial Services') ? 'selected' : ''; ?>>Banking & Financial Services</option>
                                            <option value="Healthcare" <?php echo ($industry == 'Healthcare') ? 'selected' : ''; ?>>Healthcare</option>
                                            <option value="Insurance" <?php echo ($industry == 'Insurance') ? 'selected' : ''; ?>>Insurance</option>
                                            <option value="Retail E-commerce" <?php echo ($industry == 'Retail E-commerce') ? 'selected' : ''; ?>>Retail & E-commerce</option>
                                            <option value="Telecommunications" <?php echo ($industry == 'Telecommunications') ? 'selected' : ''; ?>>Telecommunications</option>
                                            <option value="Travel Hospitality" <?php echo ($industry == 'Travel Hospitality') ? 'selected' : ''; ?>>Travel & Hospitality</option>
                                            <option value="Logistics" <?php echo ($industry == 'Logistics') ? 'selected' : ''; ?>>Logistics</option>
                                            <option value="Real Estate" <?php echo ($industry == 'Real Estate') ? 'selected' : ''; ?>>Real Estate</option>
                                            <option value="Energy Utility" <?php echo ($industry == 'Energy Utility') ? 'selected' : ''; ?>>Energy & Utility</option>
                                            <option value="IT Technology" <?php echo ($industry == 'IT Technology') ? 'selected' : ''; ?>>IT & Technology</option>
                                            <option value="Education E-Learning" <?php echo ($industry == 'Education E-Learning') ? 'selected' : ''; ?>>Education & E-Learning</option>
                                            <option value="FMCG" <?php echo ($industry == 'FMCG') ? 'selected' : ''; ?>>FMCG</option>
                                            <option value="Manufacturing Supply Chain" <?php echo ($industry == 'Manufacturing Supply Chain') ? 'selected' : ''; ?>>Manufacturing & Supply Chain</option>
                                            <option value="Media Entertainment" <?php echo ($industry == 'Media Entertainment') ? 'selected' : ''; ?>>Media & Entertainment</option>
                                            <option value="NGOs" <?php echo ($industry == 'NGOs') ? 'selected' : ''; ?>>NGOs</option>
                                        </select>
                                        <br>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Email ID</label>
                                        <input type="text" id="email_id" value="<?php echo $email_id;?>" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Contact Number</label>
                                        <input type="text" id="contact_number" value="<?php echo $contact_number;?>" class="form-control"> <br><br>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Category</label>
                                        <select id="category" name="category" class="form-control">
                                            <option value="Branding" <?php echo ($category == 'Branding') ? 'selected' : ''; ?>>Branding</option>
                                            <option value="Digital" <?php echo ($category == 'Digital') ? 'selected' : ''; ?>>Digital</option>
                                            <option value="Packaging" <?php echo ($category == 'Packaging') ? 'selected' : ''; ?>>Packaging</option>
                                            <option value="E-commerce" <?php echo ($category == 'E-commerce') ? 'selected' : ''; ?>>E-commerce</option>
                                        </select>
                                        <br><br>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Looking For ?</label>
                                        <input type="text" id="services_looking" value="<?php echo $services_looking;?>" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Channel</label>
                                        <input type="text" id="channel" value="<?php echo $channel;?>" class="form-control">
                                        <br><br>
                                    </div>
                                  
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Status</label>
                                        <select id="statuss" name="status" class="form-control">
                                            <option value="New Lead" <?php echo ($status == 'New Lead') ? 'selected' : ''; ?>>New Lead</option>
                                            <option value="Open" <?php echo ($status == 'Open') ? 'selected' : ''; ?>>Open</option>
                                            <option value="In Progress" <?php echo ($status == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                            <option value="Quotation Shared" <?php echo ($status == 'Quotation Shared') ? 'selected' : ''; ?>>Quotation Shared</option>
                                            <option value="On Boarded" <?php echo ($status == 'On Boarded') ? 'selected' : ''; ?>>On Boarded</option>
                                            <option value="Dropout" <?php echo ($status == 'Dropout') ? 'selected' : ''; ?>>Dropout</option>
                                        </select>
                                        <br><br>
                                    </div>
                                </div>
 <!-- /****************************************************** add note****************************************************/ -->

                                <div class="row">
                                    <form id="leadform" method="POST">
                                        <div class="col-md-6">
                                            <label class="ctitle">Notes</label>
                                            <!-- TinyMCE Editor -->
                                            <textarea id="notes" class="tinymce-editor">
                                                
                                            </textarea><!-- End TinyMCE Editor --> <br> 
                                            <input type="hidden" id="emp_id" class="form-control" value="<?php echo $eid;?>">  
                                            <input type="hidden" id="lead_id" class="form-control" value="<?php echo $lead_id_value;?>">  

                                            <button type="button" id="addcomment"  name="eid" class=" btn bg-primary text-white px-3 py-2"> Add Comment </button>
                                        </div>
                                        </form>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold" for="notes">All Notes</label>
                                        <div id="drop-area" class="drop-area" style="height: 370px; overflow-x:auto">
                                            <div class="">
                                                <div style="text-align: justify;">
                                                <p><?php echo $decode_notes; ?></p>    
                                                    <hr>
                                                </div>
                                                    <?php 
                                                        get_notes($base_url,$db, $lead_id);
                                                    ?>    
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                   <br>
<!-- /********************************************************************************************************/ -->
                                <input type="hidden" id="lead_id" class="form-control" value="<?php echo $lead_id_value;?>">  

                                <div class="float-left" style="float: left;">
                                    <input type="button" class="btn mt-3" id="updateLead" name="updateLead" value="Update" style="background-color: #012970;color:#fff; margin-left: 480px;">
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>


    </section>

</main><!-- End #main -->

<?php 
require('../footer.php');
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://dds.doodlodesign.com/assets/vendor/tinymce/tinymce.min.js"></script>

<!-- add text editor -->

<script>
tinymce.init({
  selector: 'textarea'
});
</script>

<!-- ajax code for updating task -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    $(document).ready(function () {
        $('#updateLead').click(function () {
            var lead_id = $('#lead_id').val();
            var date = $('#date').val(); 
            var client_name = $('#client_name').val();
            var business_name = $('#business_name').val();
            var industry = $('#industry').val();
            var email_id = $('#email_id').val();
            var contact_number = $('#contact_number').val();
            var category = $('#category').val();
            var services_looking = $('#services_looking').val();
            var channel = $('#channel').val();
            var status = $('#statuss').val();
            var notes = tinymce.get('notes').getContent();
            $.ajax({
               url: "../../API/update_lead_api.php",
                type: 'POST',
                data:{ 
                    ops: 'updateLead', 
                    lead_id: lead_id, 
                    date: date,
                    client_name: client_name,
                    business_name: business_name,
                    industry: industry,
                    email_id: email_id,
                    contact_number: contact_number,
                    category: category,
                    services_looking: services_looking,
                    channel: channel,
                    status: status,
                    notes: notes
                },
                success: function (response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Show SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            // title: 'Success',
                            text: data.message,
                            didClose: function() {
                                // Reload the page
                                window.location.reload();
                            }
                        });
                    } else {
                        // Show SweetAlert error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Show error message if AJAX request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating Lead: ' + error
                    });
                }
            });
        });
    });
</script>

<!-- /*********************** Add comment script **************************/ -->
<!-- <script>
$(document).ready(function() {
    $('#addcomment').click(function(e) {
        e.preventDefault();

        var notes = tinymce.get('notes').getContent().trim();
        var eid = $('#emp_id').val().trim();
        var lead_id = $('#lead_id').val().trim();


        if (notes !== "" && eid !== "" && lead_id !== "" ) {
            $.ajax({
                type: "POST",
                url: "../../API/add_lead_lotes_api.php",
                data: { ops: 'add_notes', notes: notes , eid: eid , lead_id: lead_id},
                success: function(response) {
                    // Check if the response contains an error message
                    if (response.startsWith('Error:')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response
                        });
                    } else {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Note added successfully!',
                            text: response
                        });
                    }
                    $('#leadform').trigger('reset'); // Reset the form
                    tinymce.get('notes').setContent(''); // Clear TinyMCE content
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again later.'
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Required!',
                text: 'Please add note.'
            });
        }
    });
});
</script> -->

<script>
$(document).ready(function() {
    // Function to add comment
    function addComment() {
        var notes = tinymce.get('notes').getContent().trim();
        var eid = $('#emp_id').val().trim();
        var lead_id = $('#lead_id').val().trim();

        if (notes !== "" && eid !== "" && lead_id !== "" ) {
            $.ajax({
                type: "POST",
                url: "../../API/add_lead_lotes_api.php",
                data: { ops: 'add_notes', notes: notes , eid: eid , lead_id: lead_id},
                success: function(response) {
                    if (response.startsWith('Error:')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response
                        });
                    } else {
                        // Swal.fire({
                        //     icon: 'success',
                        //     title: 'Note added successfully!',
                        //     text: response
                        // });
                        // Reload the page after successful submission
                        location.reload();
                    }
                    $('#leadform').trigger('reset'); // Reset the form
                    tinymce.get('notes').setContent(''); // Clear TinyMCE content
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again later.'
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Required!',
                text: 'Please add note.'
            });
        }
    }

    // Bind click event to add comment button
    $('#addcomment').click(function(e) {
        e.preventDefault();
        addComment(); // Call the function when button is clicked
    });
});
</script>

