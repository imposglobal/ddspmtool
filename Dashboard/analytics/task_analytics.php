<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');

?>
<style>
    .offcanvas, .offcanvas-lg, .offcanvas-md, .offcanvas-sm, .offcanvas-xl, .offcanvas-xxl 
    {
    --bs-offcanvas-width: 550px !important;
    }

    .edit {
    font-size: 14px;
    olor: #012970;
    font-family: "Poppins", sans-serif;
    font-weight: 500;
   }

   .exportbtn
    {
      background-color: #012970;
      color:#fff;
    }
    .exportbtn:hover
    {
      background-color: #012970;
      color: #fff;
    }
 
</style>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Projects</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">Projects Analytics</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
    <div class="col-lg-12">
    <form method="post" action="../../API/export_projects.php">
    <div class="row">
     <!-- project Name -->  
    <div class="col-lg-3 mb-4">  
    <select class="form-select" name="project_id">
    <option selected>Select Project</option>
    <option value="All">All</option>
    <?php
    $sql = "SELECT * from projects ORDER BY created_at DESC";
    $result = mysqli_query($db, $sql);
    if ($result && mysqli_num_rows($result) > 0)
    {
     while ($row = mysqli_fetch_assoc($result))
    {
    ?>    
    <option value="<?php echo $row["pid"]?>"><?php echo $row["project_name"]?></option>
    <?php 
    }
    }
    ?>
    </select>
    </div>
<!-- project Name -->
 <!-- status -->
<div class="col-lg-3 mb-4">  
<select class="form-select" name="time_status">
    <option selected>Select Time</option>
    <option value="today">Today</option>
    <option value="yesterday">Yesterday</option>
    <option value="weekly">Weekly</option>
    <option value="monthly">Monthly</option> 
</select>
 </div>
 <!-- status -->

 <!-- start date -->
 <div class="col-lg-2 mb-4">  
 <input type="date" class="form-control" name="start_date">
 </div>
 <!-- start date -->

<!-- end date -->
<div class="col-lg-2 mb-4">  
<input type="date" class="form-control" name="end_date">
</div>
<!-- end date -->




    <div class="col-lg-2 text-start">
    <button type="submit" class="btn exportbtn">Export</button>  
    </div>
    </form>
    </div>
    </div>

    <!-- end of div -->
       
        <div class="col-lg-12">

          <div class="card">
            <div class="row">   
                <div class="col-lg-12">
                    <div class="card-body">
                        <h5 class="card-title pb-1 pt-4"> Projects</h5>
                        <hr>
                <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Project Name</th>
                    <th scope="col">Total Task</th>
                    <th scope="col">Total Employee</th>
                    <th scope="col">Employee Name</th> 
                    <th scope="col">Total Time (Hrs)</th>                  
                    <th scope="col">Total Break</th>
                                           
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    get_task_analytics($db, $page, $recordsPerPage);
                  ?> 
                </tbody>
              </table>

                    </div>
                </div>
            </div>
          </div>
       
        
      </div>
      <?php 
      //for project pagination 
      if($role == 0){
        $sql = "SELECT COUNT(*) AS total FROM projects";
      }else{
        $sql = "SELECT COUNT(*) AS total FROM projects WHERE eid ='$eid'";
      }
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_assoc($result);
      $totalRecords = $row['total'];
      $totalPages = ceil($totalRecords / $recordsPerPage);
      
      pagination($page, $totalPages);
      ?>
    </section>

  </main><!-- End #main -->



 
<?php 
require('../footer.php');
?>