<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');
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
.icon{
      font-size:20px;
      margin:0 5px;
      cursor: pointer;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Projects</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">Assign Projects</li>
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
                        <!-- <h5 class="card-title pb-1 pt-4">Assign Project</h5> -->
                    
                        <div class="row mt-3">
                            <div class="col-lg-12">
                            <form method="post" id="assigned_form">  
                     <!-- Fetch Projects -->
                    <h5 class="card-title">Select Project</h5>                    
                    <select id="project_id" class="form-select" aria-label="Default select example">
                    
                    <option selected="" disabled="true">Select Project</option>
                    <?php
                         $sql = "SELECT * FROM projects";
                         $result = mysqli_query($db, $sql);
                         if ($result && mysqli_num_rows($result) > 0)
                         {
                            while ($row = mysqli_fetch_assoc($result))
                            {
                            ?>
                    <option value="<?php echo $row["pid"];?>"><?php echo $row["project_name"];?></option>
                    <?php   
                         }
                         }
                         ?>
                    </select>


                    <!-- Fetch employee -->

                    <h5 class="card-title">Select Employee</h5>                    
                    <select id="employee_id" class="form-select" aria-label="Default select example">                    
                    <option selected="" disabled="true">Select Employee</option>
                    <?php
                         $sql = "SELECT * FROM employees";
                         $result = mysqli_query($db, $sql);
                         if ($result && mysqli_num_rows($result) > 0)
                         {
                            while ($row = mysqli_fetch_assoc($result))
                            {
                            ?>
                   <option value="<?php echo $row["eid"];?>"><?php echo $row["fname"];?> <?php echo $row["lname"];?></option>
                    <?php   
                         }
                         }
                         ?>
                    </select>
                        
                    <button id="save" type="button" class="btn mt-4 px-4 py-2 btn-md mb-4 text-white" style="background-color: #012970;"><i class="bi bi-file-earmark-plus-fill "></i> Add Project</button>
                    </form>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
   // Function to handle form submission with jQuery AJAX
$(document).ready(function() {
    $('#save').click(function(e) {
        e.preventDefault();
        var project_id = $('#project_id').val()
        var employee_id = $('#employee_id').val()
        if(project_id !== "" && employee_id !== "" ) {
            // AJAX request
            $.ajax({
                type: "POST",
                url: "../../API/insert.php",
                data: { ops: 'assign_project', project_id: project_id,  employee_id: employee_id},
                success: function(response) {
                    // Use SweetAlert for displaying success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response
                    });
                      // Reset the form
                    //   $('#project_id').val('');
                    //   $('#employee_id').val('');
                      // $('#assigned_form').trigger('reset');
                      $('#assigned_form')[0].reset();
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
                text: "Please Select all the fields"
            });
        }
    });
});
</script>
