<?php 
require('../header.php');
?>
<title>My Tasks - DDS</title>
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


  /* style for alert box */

  .alert-box {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fffefe;
    /* border: 1px solid #ccc; */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    padding: 30px 30px;
    z-index: 9999;
    width: 32em;
    border-radius: 10px;
    font-size: 1rem;
  }

  .alert-box select {
    display: block;
    margin-bottom: 30px;
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  .alert-box button {
    display: block;
    width: 100%;
    padding: 8px 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .alert-box button:hover {
    background-color: #0056b3;
  }

  .taskmessage {
    color: #012970;
    font-size: 13px;
    font-family: "Open Sans", sans-serif;
    padding-top: 3px;

  }

  .exportbtn {
    background-color: #012970;
    color: #fff;
  }


  #status>option:nth-child(2) {
    background-color: green;
    color: #fff;
  }

  #status>option:nth-child(3) {
    background-color: #dec016;
    color: #fff;
  }

  #status>option:nth-child(4) {
    background-color: #eb7e09;
    color: #fff;
  }

  #status>option:nth-child(5) {
    background-color: #eb6709;
    color: #fff;
  }

  #status>option:nth-child(6) {
    background-color: red;
    color: #fff;
  }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<main id="main" class="main">

  <div class="pagetitle">
    <h1>Tasks</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item active">Tasks</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
   
    <!-- filter -->
    <div class="col-lg-12">
      <form method="GET" action="../../API/export_task.php">
        <div class="row">
          <!-- project Name -->
          <div class="col-lg-3 mb-4">
            <select class="form-select" name="project_id">
              <option selected disabled="true">Select Project</option>
              <option value="All">All</option>
              <?php
                  $sql = "SELECT * from projects ORDER BY created_at DESC";
                  $result = mysqli_query($db, $sql);
                  if ($result && mysqli_num_rows($result) > 0)
                  {
                  while ($row = mysqli_fetch_assoc($result))
                  {
               ?>
                <option value="<?php echo $row["pid"]?>">
                   <?php echo $row["project_name"]?> 
                </option>

              <?php 
    }
    }
    ?>
            </select>
          </div>
          <!-- project Name -->

<!-- project type -->
<div class="col-lg-3 mb-4">  
<select class="form-select" name="project_type">
<option selected="">Select Project Type</option>
<option value="All">All</option>
<option value="social-media">Social Media</option>
<option value="branding">Branding</option>
<option value="packaging">Packaging</option>
<option value="ux-ui">UX & UI</option>
<option value="development">Development</option> 
</select>
 </div>
<!-- project type -->


          <!-- employee_name -->

     <?php if($role == 0)
     { 
        ?>
        <div class="col-lg-3 mb-4">  
        <select class="form-select" name="employee_id">
        <option selected disabled="true">Select Employee</option>
        <option value="All">All</option>
        <?php
        $sql = "SELECT * from employees where role = '1'";
        $result = mysqli_query($db, $sql);
        if ($result && mysqli_num_rows($result) > 0)
        {
         while ($row = mysqli_fetch_assoc($result))
        {
        ?>    
        <option value="<?php echo $row["eid"]?>"><?php echo $row["fname"]?></option>
        <?php 
        }
        }
        ?>
        </select>
        </div>

   <?php  } 
     else
     {
    ?>
    
    <input type="hidden" Readonly class="form-control" name="employee_id" value="<?php echo $eid ?>">
    
    <?php 
    }
     
     ?>
   
   
<!-- employee_name -->

          <!-- task status -->
          <div class="col-lg-3 mb-4">
            <select id="status" class="form-select" name="task_status">
              <option selected="" disabled="true">Select Status</option>
              <option value="Completed">Completed</option>
              <option value="In Progress">In Progress</option>
              <option value="Pending">Pending</option>
              <option value="On Hold">On Hold</option>
              <option value="Abonded">Abonded</option>
            </select>
          </div>
          <!-- task status -->
          <!-- time -->
          <div class="col-lg-3 mb-4">
            <select class="form-select" name="time_status">
              <option selected disabled="true">Select Time</option>
              <option value="today">Today</option>
              <option value="yesterday">Yesterday</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
            </select>
          </div>
          <!-- time-->

          <!-- start date -->
          <div class="col-lg-3 mb-4">  
          <input type="date" class="form-control" name="start_date">
          </div>
          <!-- start date -->

          <!-- end date -->
         <div class="col-lg-3 mb-4">  
         <input type="date" class="form-control" name="end_date">
         </div>
         <!-- end date -->


          <div class="col-lg-3 text-start">
            <button type="submit" formaction="task_by_filter.php" class="btn btn-info">Show</button>
            <button type="submit" class="btn exportbtn ms-2">Export</button>
          </div>
      </form>
    </div>
    </div>
   
    <!-- end of div -->

    <!-- filter -->
    <div class="col-lg-12">
      <div class="card">
        <div class="row">
          <div class="col-lg-12">
            <div class="card-body">
              <h5 class="card-title pb-1 pt-4"> Tasks</h5>
              <hr>



              <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    get_tasks_by_filter($role,$eid,$db, $page, $recordsPerPage);
                  ?>

            </div>
          </div>
        </div>
      </div>

    </div>

    </div>

    <!-- pagination -->
    <?php  

?>

  </section>

</main><!-- End #main -->


<?php 
require('../footer.php');
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>