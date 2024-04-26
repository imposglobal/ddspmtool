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

</style>



 

<main id="main" class="main">
<section class="section profile">
<div class="row">
<div class="col-xl-12 text-end mb-3">
     <button class="btn  btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
      Edit Task
     </button>
</div>
</div>
<?php
  if(isset($_GET['tid']))
  {
    $tid = $_GET['tid'];
  }
    $sql = "SELECT task.tid, task.start_date, task.end_date, task.task_type, task.title, task.description, task.status, task.estimated_time, task.m_status, task.priority, task.feedback, task_time.total_time, task_time.initial_time, task_time.end_time, time_difference.time,  time_difference.reason 
    FROM task INNER JOIN task_time ON task.tid = task_time.tid INNER JOIN time_difference ON task.tid = time_difference.tid
    WHERE task.tid = '$tid'";
    $query = mysqli_query($db, $sql);
    if ($query && mysqli_num_rows($query) > 0)
    {
    while ($row = mysqli_fetch_assoc($query))
    {
    $ttime = $row["total_time"];
    $dtime = $row["time"];
    $itime = $row["initial_time"];
    $etime = $row["end_time"];
    $rtime = $row["reason"];
    if ($ttime === '' && $dtime === '' && $itime === '' && $etime === '' && $rtime === '') 
    {
        $sql = "SELECT * FROM task WHERE tid = '$tid'";
    } 
    else
    {

    $sql = "SELECT DISTINCT  
    task.tid, 
    task.start_date, 
    task.end_date, 
    task.task_type, 
    task.title, 
    task.description, 
    task.status, 
    task.estimated_time, 
    task.m_status, 
    task.priority, 
    task.feedback, 
    task_time.total_time, 
    time_difference.time, 
    time_difference.reason, 
    total_time_subquery.total_break_time 
FROM 
    task 
INNER JOIN 
    task_time ON task.tid = task_time.tid
INNER JOIN 
    time_difference ON task.tid = time_difference.tid
INNER JOIN (
    SELECT 
        tid, 
        SEC_TO_TIME(SUM(TIME_TO_SEC(time))) AS total_break_time 
    FROM 
        time_difference 
    WHERE 
        tid = '$tid' 
    GROUP BY 
        tid
    LIMIT 1
) AS total_time_subquery ON task.tid = total_time_subquery.tid 
WHERE 
    task.tid = '$tid'
LIMIT 1; "; 
}
    $result = mysqli_query($db, $sql);
    $priority = ''; 
    $status = '';

    if ($result && mysqli_num_rows($result) > 0)
    {
    while ($row = mysqli_fetch_assoc($result))
    {

    $total_break_time = $row["total_break_time"];

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


      // Calculate Time Frame
//    Split the time strings into hours, minutes, and seconds
list($total_hours, $total_minutes, $total_seconds) = explode(':', $row["total_time"]);
list($difference_hours, $difference_minutes, $difference_seconds) = explode(':', $row["time"]);

// Calculate the total time and time difference in seconds
$totaltime_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;
$difference_seconds = $difference_hours * 3600 + $difference_minutes * 60 + $difference_seconds;

// Calculate the difference in seconds
$difference_seconds = $totaltime_seconds - $difference_seconds;

// Convert the difference to minutes
$timeframe= round($difference_seconds / 60);

?>
 
   <div class="row">      
        <div class="col-xl-12">
        <div class="card">
        <div class="card-body">

        
        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Task Title :</h4>
                <h6 class="card-subtitle d-inline ms-2 ps-2"><?php echo $row["title"];?></h6>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Status :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $status;?></h6>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Time Frame :</h4>

               <!-- To show minute and Hours According to the output -->

             <?php 
             if ($timeframe >= 60)
             {
                $hours = floor($timeframe / 60);
                $remaining_minutes = $timeframe % 60;
                // Display the result in hours and minutes format
                echo "<h6 class='card-subtitle d-inline ml-2 ps-2'>$hours Hr";
                echo ($hours > 1) ? "s" : "";
                echo " $remaining_minutes Minute";
                echo ($remaining_minutes != 1) ? "s" : "";
                echo "</h6>";
             }
             else
             {
                // Display the result in minutes format with proper grammar
                echo "<h6 class='card-subtitle d-inline ml-2 ps-2'>$timeframe Minute";              
                echo "</h6>";
             }
              ?>

            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Break :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $row['total_break_time'];?> Minute</h6><br>
                <h4 class="card-title d-inline">Reason :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $row["reason"];?></h6>
             
            </div>
        </div>
        <hr>

        

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Priority :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $priority_html;?></h6> 
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">Start Date :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $row["start_date"];?></h6>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col pt-3">
                <h4 class="card-title d-inline">End Date :</h4>
                <h6 class="card-subtitle d-inline ml-2 ps-2"><?php echo $row["end_date"];?></h6>
            </div>
        </div>
        <hr>

        <h4 class="card-title">Description</h4>
        <h6 class="card-subtitle "><?php echo $row["description"];?></h6>    
    </div>
    </div>
    </div>
    


    <?php 
     if($role == 0)
     {
      ?>
      <div class="col-lg-6">   
      <div class="card"> 
      <div class="card-body">
      <form method="POST">
      <h4 class="card-title">Manager Status</h4>
      <input type="hidden" id="tid" class="form-control" value="<?php echo $row["tid"];?>">
      <select id="m_status" class="form-select">
      <option selected value="<?php echo $row["m_status"];?>"><?php echo $row["m_status"];?></option>
      <option value="Accepted">Accepted</option>
      <option value="Rejected">Rejected</option>
      </select>    
      <input type="button" class="btn btn-primary mt-3" id="mstatus" name="mstatus" value="submit" style="background-color: #012970;color:#fff;">
      </form>
      </div>
      </div>
      </div>
      <?php
     }
    ?>

      </div>

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
                    <input type="time" id="etime" class="form-control" value="<?php echo $row["estimated_time"]; ?>">
                    
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
                    <input type="hidden" id="tid" class="form-control" value="<?php echo $row["tid"];?>">
                    
                </div>
                          
                      <div class="col-lg-12">       
                      <input type="button" class="btn mt-3" id="update_task" name="update_task" value="Update" style="background-color: #012970;color:#fff;">
                      </div>
                      </form>                      
          </div>
        </div>
        
</div>




     
      
    </section>
    <?php 
     }
     }
    }
    }
    ?>
                        
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

<!-- for updating manager status -->

<script>
    $(document).ready(function () {
        $('#mstatus').click(function () {
          // e.preventDefault();
        var tid = $('#tid').val(); 
        var m_status = $('#m_status').val();
            $.ajax({
               url: "../../API/update.php",
                type: 'POST',
                data:{ ops: 'update_mstatus', tid:tid, m_status:m_status},
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


