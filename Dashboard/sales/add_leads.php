<?php 
require('../header.php');
?>
<title>Add Leads - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');
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


</style>
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Lead Generation</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">Add Leads</li>
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
                        <h5 class="card-title pb-1 pt-4">Add Lead</h5>
                        <hr> <br>
                        <form id="leadform">
                        <div class="row mt-3">
                            <div class="col-lg-">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Client Name</label>
                                        <input type="text" id="client_name" class="form-control" placeholder=" client Name">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Business Name</label>
                                        <input type="text" id="business_name" class="form-control" placeholder=" Business Name">
                                    </div> 
                                    <br><br>
                                    <div class="col-md-4">
                                    <label class="ctitle mb-3">Industry</label>
                                    <select id="industry" class="form-control">
                                        <option value="">Select Industry</option>
                                        <option value="banking_financial_services">Banking & Financial Services</option>
                                        <option value="healthcare">Healthcare</option>
                                        <option value="insurance">Insurance</option>
                                        <option value="retail_ecommerce">Retail & E-commerce</option>
                                        <option value="telecommunications">Telecommunications</option>
                                        <option value="travel_hospitality">Travel & Hospitality</option>
                                        <option value="logistics">Logistics</option>
                                        <option value="real_estate">Real Estate</option>
                                        <option value="energy_utility">Energy & Utility</option>
                                        <option value="it_technology">IT & Technology</option>
                                        <option value="education_elearning">Education & E-Learning</option>
                                        <option value="fmcg">FMCG</option>
                                        <option value="manufacturing_supply_chain">Manufacturing & Supply Chain</option>
                                        <option value="media_entertainment">Media & Entertainment</option>
                                        <option value="ngos">NGOs</option>
                                    </select>
                                    <br><br>
                                </div>

                                   
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Email ID</label>
                                        <input type="email" id="email_id" class="form-control" placeholder=" Emial ID">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Contact Number</label>
                                        <input type="text" id="contact_number" class="form-control" placeholder="Contact Number"> <br>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Category</label>
                                        <select id="category" name="category" class="form-control">
                                            <option value="">Select Category</option>
                                            <option value="branding">Branding</option>
                                            <option value="digital">Digital</option>
                                            <option value="packaging">Packaging</option>
                                            <option value="ecommerce">E-commerce</option>
                                        </select>
                                        <br><br>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Looking For ?</label>
                                        <input type="text" id="services_looking" class="form-control" placeholder="Looking For ?"> <br><br>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Channel</label>
                                        <input type="text" id="channel" class="form-control" placeholder="Channel">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="ctitle mb-3">Status</label>
                                        <select id="statuss" class="form-control">
                                            <option value="">Select Status</option>
                                            <option value="New Lead">New Lead</option>
                                            <option value="Open">Open</option>
                                            <option value="In Progress">In Progress</option>
                                            <option value="Quotation Shared">Quotation Shared</option>
                                            <option value="On Boarded">On Boarded</option>
                                            <option value="Dropout">Dropout</option>
                                        </select>
                                        <br> <br>
                                    </div>
                                </div>

                                <label class="ctitle">Notes</label> <br> <br>
                                <!-- TinyMCE Editor -->
                                    <textarea id="notes" placeholder="Enter your notes here..." class="tinymce-editor">
                                    
                                    </textarea><!-- End TinyMCE Editor -->
                                    
                                    <div class="float-left" style="float: left;">
                                    <button id="saveBtn" type="button" class="btn mt-4 px-4 py-2 btn-md mb-4 text-white" style="background-color: #012970;"><i class="bi bi-file-earmark-plus-fill "></i> Add Lead</button>
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
<script>
   // Function to handle form submission with jQuery AJAX
$(document).ready(function() {
    $('#saveBtn').click(function(e) {
        e.preventDefault();
       
        // get form values and pass it to api using ops
       // var date = $('#date').val().trim(); 
        var client_name = $('#client_name').val().trim();
        var business_name = $('#business_name').val().trim();
        var industry = $('#industry').val().trim();
        var email_id = $('#email_id').val().trim();
        var contact_number = $('#contact_number').val().trim();
        var category = $('#category').val().trim();
        var services_looking = $('#services_looking').val().trim();
        var channel = $('#channel').val().trim();
        var status = $('#statuss').val().trim();
        var notes = tinymce.get('notes').getContent().trim();

        

        if( client_name !== "" && business_name !== "" && industry !== "" && email_id !== "" && contact_number !== "" && category !== "" && services_looking !== "" && channel !== "" && status !== "" && notes !== ""  ) {
            // AJAX request
            $.ajax({
                type: "POST",
                url: "../../API/sales_lead_generation_api.php",
                data: { ops: 'sales_lead_generation',  client_name: client_name, business_name:business_name, industry:industry, email_id:email_id, contact_number:contact_number,category:category, services_looking:services_looking, channel:channel, status:status, notes:notes,},
                success: function(response) {
                    // Use SweetAlert for displaying success message
                    Swal.fire({
                        icon: 'success',
                        title: client_name,
                        text: response
                    });
                    // Reset the form
                    $('#leadform').trigger('reset');
                    tinymce.get('notes').setContent('');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Use SweetAlert for displaying error message
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
                text: "Please fill all the fields"
            });
        }
    });
});
</script>
