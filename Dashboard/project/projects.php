<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
?>
<style>
    .ctitle {
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
      <h1>Projects</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">Projects</li>
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
                        <h5 class="card-title pb-1 pt-4">Add Project</h5>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <h5 class="ctitle mb-3">Project Name</h5>
                                <input type="text" id="pname" class="form-control">

                                <h5 class="ctitle">Write Project Description</h5>
                                    <!-- TinyMCE Editor -->
                                    <textarea id="description" class="tinymce-editor">
                                    
                                    </textarea><!-- End TinyMCE Editor -->
                                    <div class="float-left" style="float: left;">
                                    <button id="saveBtn" type="button" class="btn mt-4 px-4 py-2 btn-md mb-4 text-white" style="background-color: #012970;"><i class="bi bi-file-earmark-plus-fill "></i> Add Project</button>
                                    </div>
                            </div>
                           
                        </div>

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
        var pname = $('#pname').val().trim(); // Trim whitespace
        var description = tinymce.get('description').getContent().trim(); // Trim whitespace

        if(pname !== "" && description !== "") {
            // AJAX request
            $.ajax({
                type: "POST",
                url: "../../API/insert.php",
                data: { ops: 'project', pname: pname, description: description },
                success: function(response) {
                    // Use SweetAlert for displaying success message
                    Swal.fire({
                        icon: 'success',
                        title: pname,
                        text: response
                    });
                    // Reset the form
                    $('#pname').val('');
                    tinymce.get('description').setContent('');
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
