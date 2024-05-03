<?php 
require('../header.php');
?>
<title>My Tasks - DDS</title>
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
    .offcanvas-title
    {
      font-weight: 800;
    }
    .card-title
    {
      font-size: 15px;
    }
    .card-subtitle
    {
      font-family: "Poppins", sans-serif;
      font-size: 15px;
    }

     /* CSS rule to style the Font Awesome icon within disabled buttons */
     .start_time[disabled] .fa-play {
    color: grey;
}

.break-btn
{
    background-color: #012970;
    color: #fff;
}
.hr_margin
{
    margin-top:30px;
}

</style>



 

<main id="main" class="main">
<section class="section profile">

<!-- code for fetching task -->
<?php
if(isset($_GET['tid']))
{
  $tid = $_GET['tid'];
}
$sql = "SELECT task.tid, task.start_date, task.end_date, task.task_type, task.title, task.description, task.status, task.priority, task.estimated_time, task.m_status, task.feedback, task.pid, projects.project_name FROM `task` inner join projects on task.pid = projects.pid WHERE tid = '$tid';";
$query = mysqli_query($db, $sql);
if ($query && mysqli_num_rows($query) > 0)
{
    while ($row = mysqli_fetch_assoc($query))
    {

         // Status 
    if($row["status"] == "")
    {
      $priority = '<td> <span style="background:#fff; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
    }
   elseif($row["status"] == "Completed"){
      $status = '<td> <span style="background:green; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
   }
   elseif($row["status"] == "In Progress")
   {
    $status = '<td> <span style="background:#dec016; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
   }
   elseif($row["status"] == "Pending")
   {
    $status = '<td> <span style="background:#eb7e09; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
   }
   elseif($row["status"] == "On Hold")
   {
    $status = '<td> <span style="background:#eb6709; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
   }
   elseif($row["status"] == "Abonded")
   {
    $status = '<td> <span style="background:red; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
   }


   // Priority 
if($row["priority"] == "") {
    $priority_html = '<td> <span style="background:#fff; color:#fff; padding:2px 8px;">'. $row["priority"].' </span></td>';
}
elseif($row["priority"] == "High") {
    $priority_html = '<td> <span style="background:#ff6961; color:#fff; padding:2px 8px;">'. $row["priority"].' </span></td>';
}
elseif($row["priority"] == "Medium") {
    $priority_html = '<td> <span style="background:#ffb861; color:#fff; padding:2px 8px;">'. $row["priority"].' </span></td>';
}
elseif($row["priority"] == "Low") {
    $priority_html = '<td> <span style="background:#61ffb8; color:#fff; padding:2px 8px;">'. $row["priority"].' </span></td>';
}

        ?>
   

  
 
   <sdiv class="row">   
     <div class="col-xl-12 text-end mb-3">
     <button class="btn  btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
      Edit Task
     </button>
     </div>
   
        <div class="col-xl-12">
        <div class="card">
        <div class="card-body">


        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Project Name :</h4>
                <h6 class="card-subtitle d-inline ms-2 ps-2"><?php echo $row["project_name"];?></h6>
            </div>
        </div>
        <hr class="hr_margin">
        
        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Task Title :</h4>
                <h6 class="card-subtitle d-inline ms-2 ps-2"><?php echo $row["title"];?></h6>
            </div>
        </div>
        <hr class="hr_margin">

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Status :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $status;?></h6>
            </div>
        </div>
        <hr class="hr_margin">

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Time Frame :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2" id="timeframe_placeholder"></h6>
            </div>
        </div>
        <hr class="hr_margin">

        <div class="row">
            <div class="col-lg-6 pt-3">
                <h4 class="card-title d-inline">Total Break Time :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2" id="total_break_time"></h6><br>      
             </div>
             <div class="col-lg-6">
             <a class="btn btn-info break-btn" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                View breaks
                </a> 
             </div>
        </div>
        <hr class="hr_margin">

        <!-- drawer for showing break-->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Breaks</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
        <div id="breaksContainer">
        <!-- Breaks will be appended here -->
        </div>
        </div>
        </div>
        <!-- drawer -->

        

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Priority :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $priority_html;?></h6> 
            </div>
        </div>
        <hr class="hr_margin">


        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Estimated Time :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $row["estimated_time"];?></h6> 
            </div>
        </div>
        <hr class="hr_margin">

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Start Date :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $row["start_date"];?></h6>
            </div>
        </div>
        <hr class="hr_margin">

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">End Date :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $row["end_date"];?></h6>
            </div>
        </div>
        <hr class="hr_margin">
        
        <?php if($role !== 0){ ?>

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Manager Status :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $row["m_status"];?></h6>
            </div>
        </div>
     
        <hr class="hr_margin">
        <h4 class="card-title">Feedback</h4>
        <h6 class="card-subtitle "><?php echo $row["feedback"];?></h6>  
        <hr class="hr_margin">
        <?php } ?>
    </div>
    </div>
    </div>
    
   <!-- manager status  -->

   
    <?php 
     if($role == 0)
     {
      ?>
      <div class="col-lg-12">   
      <div class="card"> 
      <div class="card-body">
      <form method="POST">
      <h4 class="card-title">Manager Status</h4>
      <input type="hidden" id="tid" class="form-control" value="<?php echo $row["tid"];?>">
      <select id="m_status" class="form-select">
      <option selected value="<?php echo $row["m_status"];?>"><?php echo $row["m_status"];?></option>
      <option value="Revised">Revised</option>
      <option value="Accepted">Accepted</option>
      <option value="Rejected">Rejected</option>
      <option value="Initiated">Initiated</option>
      <option value="On hold">On hold</option>
      </select>  
      
      <h5 class="card-title edit">Feedback</h5>
      <textarea id="feedback" value=""><?php echo $row["feedback"];?></textarea>
      <input type="button" class="btn btn-primary mt-3" id="mstatus" name="mstatus" value="submit" style="background-color: #012970;color:#fff;">
      </form>
      </div>
      </div>
      </div>
      <?php
     }
    ?>

 <!-- end manager status  -->




<!-- code for update task -->

      

       <!-- start of drawer -->
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
                      <select id="status" class="form-select">
                        <option selected value="<?php echo $row["status"];?>"><?php echo $row["status"];?></option>
                        <option value="Completed">Completed</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Pending">Pending</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Abonded">Abonded</option>
                      </select>

                       <h5 class="card-title edit">Task Type</h5>
                       <select id="task_type" class="form-select">
                        <option selected value="<?php echo $row["task_type"];?>"><?php echo $row["task_type"];?></option>
                        <option value="Research">Research</option>
                        <option value="Original Project">Original Project</option>
                        <option value="Changes">Changes</option>
                      </select>

                    <h5 class="card-title edit">Title</h5>
                    <input type="text" id="title" class="form-control" value="<?php echo $row["title"];?>">
                    
                    <h5 class="card-title edit">Time Frame</h5>
                    <input type="text" id="etime" class="form-control" value="<?php echo $row["estimated_time"]; ?>">
                   
                    
                    <h5 class="card-title edit">Priority</h5>    
                    <select id="priority" class="form-select">
                        <option selected value="<?php echo $row["priority"];?>"><?php echo $row["priority"];?></option>
                        <option value="High" style="background-color: #ff6961; color: white;">High</option>
                        <option value="Medium"  style="background-color: #ffb861; color: white;">Medium</option>
                        <option value="Low"  style="background-color:  #C1E1C1; color: white;">Low</option>
                    </select>

                   

                      <!-- TinyMCE Editor -->
                    <h5 class="card-title edit">Description</h5>
                    <textarea id="editor1" name="editor1" value=""><?php echo $row["description"];?></textarea>
                    </div>


                      <input type="hidden" id="tid" class="form-control" value="<?php echo $row["tid"];?>">  
                      <div class="col-lg-12">       
                      <input type="button" class="btn mt-3" id="update_task" name="update_task" value="Update" style="background-color: #012970;color:#fff;">
                      </div>
                      </form>                      
                </div>
               </div>
        
</div>

   <!-- end of drawer -->


     <!-- end of row -->
    </div>
    <?php 
    }
    }
    ?>
      
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
        $('#update_task').click(function () {
            var tid = $('#tid').val(); 
            var sdate = $('#sdate').val();
            var edate = $('#edate').val();
            var status = $('#status').val();
            var task_type = $('#task_type').val();
            var title = $('#title').val();
            var etime = $('#etime').val();
            var priority = $('#priority').val();
            var editor1 = tinymce.get('editor1').getContent();
            $.ajax({
               url: "../../API/update.php",
                type: 'POST',
                data:{ 
                    ops: 'update_task', 
                    tid: tid, 
                    sdate: sdate, 
                    edate: edate, 
                    status: status, 
                    task_type: task_type, 
                    title: title,
                    etime: etime, 
                    priority: priority, 
                    editor1: editor1
                },
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
                        // Reload the page
                        // window.location.reload();
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

<!-- ajax code for updating manager status -->

<script>
    $(document).ready(function () {
        $('#mstatus').click(function () {
          // e.preventDefault();
        var tid = $('#tid').val(); 
        var m_status = $('#m_status').val();
        var feedback = tinymce.get('feedback').getContent();
        // var feedback = $('#feedback').val();
            $.ajax({
               url: "../../API/update.php",
                type: 'POST',
                data:{ ops: 'update_mstatus', tid:tid, m_status:m_status, feedback: feedback},
                success: function (response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Show SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message
                            // didClose: function() 
                            // {
                            // // Reload the page
                            // window.location.reload();
                            // }
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

<!-- Get total time frame -->

<script>
function view_timeframe(tid) 
{
    $.ajax({
        url: "../../API/get.php",
        type: "GET",
        dataType: "JSON",
        data: {
            ops: 'view_time',
            tid: tid
        },
        success: function(data) 
        {
            var timeframe = data.timeframe;
            // checks if a variable named timeframe is not null and not undefined
            if (timeframe !== null && timeframe !== undefined) 
            {
                // If the timeframe is greater than or equal to 60, it calculates the number of hours and remaining minutes from the timeframe
                if (timeframe >= 60) {
                    var hours = Math.floor(timeframe / 60);
                    var remaining_minutes = timeframe % 60;
                    var displayString = (hours > 0 ? hours + ' hr ' : '') + remaining_minutes + ' m';
                    // Display the result in hours and minutes format
                    $('#timeframe_placeholder').html(displayString);
                } else {
                    // Display the result in minutes format 
                    $('#timeframe_placeholder').html(timeframe + 'm');
                }
            } 
            else 
            {
                // Handle case when timeframe is null or undefined
                $('#timeframe_placeholder').html('Not Started');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Unable To Load Data');
        }
    });
}
view_timeframe(<?php echo $tid; ?>);
</script>

<!-- Get sum of total break time -->
<script>
function view_total_break_time(tid) {
    $.ajax({
        url: "../../API/get.php?tid=" + tid, 
        type: "GET",
        dataType: "JSON",
        data:
        {
            ops: 'view_total_break_time' 
        },
        success: function (data) 
        {           
            $('#total_break_time').html(data.total_break_time);  
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Unable To Load Data');
        }
    });
}
view_total_break_time(<?php echo $tid; ?>);
</script>


<!-- ajax code for view all breaks  -->

<script>
function view_breaks(tid) {
    $.ajax({
        url: "../../API/get.php?tid=" + tid, 
        type: "GET",
        dataType: "JSON",
        data:
        {
            ops: 'view_breaks' 
        },
        success: function (data) 
        {
            if (data.error) {
                // Handle case when there's an error
                console.error(data.error);
                return;
            }
             // Clear previous content
             $('#breaksContainer').empty();
            
            // code to retrieve all breaks within the loop for a particular ID.
            data.forEach(function(record){
                $('#breaksContainer').append(
                    // Loop through each record and append to the container
                    `<div class="col pt-3">
                        <h4 class="card-title d-inline">Time:</h4>
                        <h6 class="card-subtitle d-inline ml-2 ps-2">${record.time} M</h6><br>   
                        <h4 class="card-title d-inline">Reason:</h4>
                        <h6 class="card-subtitle d-inline ml-2 ps-2">${record.reason}</h6>         
                    </div>`
                );
            });

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Unable To Load Data');
        }
    });
}
view_breaks(<?php echo $tid; ?>);
</script>





