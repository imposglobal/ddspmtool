<?php 
require('../header.php');
?>
<title>My Tasks - DDS</title>
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

<?php
if(isset($_GET['tid'])){
    $tid = $_GET['tid'];
  }

  $sql = "SELECT * FROM task WHERE tid = '$tid'";
  $result = mysqli_query($db, $sql);

  if ($result && mysqli_num_rows($result) > 0)
{
  while ($row = mysqli_fetch_assoc($result))
  {
    ?>

 

<main id="main" class="main">

    

    <section class="section profile">
      <div class="row">
        <div class="col-xl-12 text-end mb-3">
            <button class="btn  btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
            Edit Task
            </button>
        </div>
        <div class="col-xl-12">
        <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Task Title</h4>
                            <h6 class="card-subtitle "><?php echo $row["title"];?></h6>
                            <hr>

                            <h4 class="card-title">Status</h4>
                            <h6 class="card-subtitle "><?php echo $row["status"];?></h6>
                            <hr>

                            <h4 class="card-title">Time Frame</h4>
                            <h6 class="card-subtitle "><?php echo $row["timeframe"];?>  Hrs</h6>
                            <hr>

                            <h4 class="card-title">Start Date</h4>
                            <h6 class="card-subtitle "><?php echo $row["start_date"];?></h6>
                            <hr>

                            <h4 class="card-title">End Date</h4>
                            <h6 class="card-subtitle "><?php echo $row["end_date"];?></h6>
                            <hr>

                            <h4 class="card-title">Description</h4>
                            <h6 class="card-subtitle "><?php echo $row["description"];?></h6>
                            <hr>
                         </div>


                        
                    </div>

        </div>

        
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Edit Task</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <!-- form -->
   <form method="POST">
         
            <div class="row">
                <div class="col-lg-12">        
                    <h5 class="card-title edit">Start Date</h5>
                    <input type="date" id="sdate" class="form-control" value="<?php echo $row["start_date"];?>">

                    <h5 class="card-title edit">End Date</h5>
                    <input type="date" id="edate" class="form-control" value="<?php echo $row["end_date"];?>">

                    <h5 class="card-title edit">Status</h5>
                    <input type="text" id="status" class="form-control" value="<?php echo $row["status"];?>">

                    <h5 class="card-title edit">Task Type</h5>
                    <input type="text" id="task_type" class="form-control" value="<?php echo $row["task_type"];?>">

                    <h5 class="card-title edit">Title</h5>
                    <input type="text" id="title" class="form-control" value="<?php echo $row["title"];?>">

                    <h5 class="card-title edit">Time Frame</h5>
                    <input type="text" id="time_frame" class="form-control" value="<?php echo $row["timeframe"];?>">

                      <!-- TinyMCE Editor -->
                    <h5 class="card-title edit">Description</h5>
                    <textarea id="editor1" name="editor1" value=""><?php echo $row["description"];?></textarea>


                    <input type="hidden" id="tid" class="form-control" value="<?php echo $row["tid"];?>">
                    
                </div>
                <?php 
                          }
                          }
                          ?>
                                   
                      <input type="button" class="btn btn-primary mt-3" id="update_task" name="update_task" value="Update">
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
<script>
tinymce.init({
  selector: 'textarea'
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    $(document).ready(function () {
        $('#update_task').click(function () {
          // e.preventDefault();
        var tid = $('#tid').val(); 
        var sdate = $('#sdate').val();
        var edate = $('#edate').val();
        var status = $('#status').val();
        var task_type = $('#task_type').val();
        var title = $('#title').val();
        var time_frame = $('#time_frame').val();
        var editor1 = tinymce.get('editor1').getContent();

            $.ajax({
               url: "../../API/update.php",
                type: 'POST',
                data:{ ops: 'update_task', tid:tid, sdate:sdate, edate:edate, status:status, task_type:task_type, title:title, time_frame:time_frame, editor1:editor1},
                success: function (response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Show SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message
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



