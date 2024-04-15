<?php 
require('../header.php');
?>
<title>My Projects - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');
?>
<style>
   .edit
   {
    font-size:14px;
   }

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

    .offcanvas, .offcanvas-lg, .offcanvas-md, .offcanvas-sm, .offcanvas-xl, .offcanvas-xxl 
    {
    --bs-offcanvas-width: 550px !important;
    }

</style>


<main id="main" class="main">
    <section class="section profile">
      <div class="row">
        <div class="col-xl-12 text-end mb-3">
            <button class="btn  btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
            Edit Project
            </button>
        </div>
        <div class="col-xl-12">
        <div class="card">
        <?php
if(isset($_GET['pid']))
{
    $pid = $_GET['pid'];
    $sql = "SELECT * FROM projects WHERE projects.pid = '$pid'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_assoc($result))
    {
        ?>

    
                        <div class="card-body">

                        
                            <h4 class="card-title">Employee Name</h4>
                            <h6 class="card-subtitle "><?php $fname = get_assigned_project($db, $pid);?></h6>
                            <hr> 
    
                            <h4 class="card-title">Project Name</h4>
                            <h6 class="card-subtitle "><?php echo $row["project_name"];?></h6>

                            <hr>                           
                            <h4 class="card-title">Description</h4>
                            <p class="card-subtitle "><?php echo $row["description"];?></p>
                            <hr>

                            
                           <h4 class="card-title">Status</h4>
                           <h6 class="card-subtitle "><?php echo $row["status"];?></h6>

                            <hr>

                            
        <h4 class="card-title">Created Date</h4>
<h6 class="card-subtitle "><?php echo $row["created_at"];?></h6>
                 
                         </div>

                         
                        
                    </div>

        </div>

        
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Edit Project</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <!-- form -->
   <form method="POST">
         
            <div class="row">
                <div class="col-lg-12">   
                    
                

                    <h5 class="card-title edit">Project Name</h5>
                    <input type="text" id="project_name" class="form-control" value="<?php echo $row["project_name"];?>">
   
    

                    <h5 class="card-title edit">Status</h5>
                    <input type="text" id="status" class="form-control" value="<?php echo $row["status"];?>">


                    <h5 class="card-title edit">Description</h5>
                    <textarea id="editor1" name="editor1" value=""><?php echo $row["description"];?></textarea>

                    <input type="hidden" id="pid" class="form-control" value="<?php echo $row["pid"];?>">
                    
                </div>
                <?php       
                    }
                    }
                    ?>
               <div class="col-lg-12">
                      <input type="button" class="btn mt-3" id="update_project" name="update_project" value="Update" style="background-color: #012970;color:#fff;">
                </div>
                        </form>
                       
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
tinymce.init({
  selector: 'textarea'
});
</script>
<script>
    $(document).ready(function () {
        $('#update_project').click(function () {
          // e.preventDefault();
        var pid = $('#pid').val(); 
        var project_name = $('#project_name').val();
        var status = $('#status').val();
        var editor1 = tinymce.get('editor1').getContent();

            $.ajax({
               url: "../../API/update.php",
                type: 'POST',
                data:{ ops: 'update_project', pid:pid, project_name:project_name, status: status, editor1:editor1},
                success: function (response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Show SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            didClose: function() 
                            {
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
                        text: 'Error updating Task: ' + error
                    });
                }
            });
        });
    });
</script>






