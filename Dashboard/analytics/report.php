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
    width:32em;   
    border-radius:10px;
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

.taskmessage
{
    color: #012970;
    font-size: 13px;
    font-family: "Open Sans", sans-serif;
    padding-top:3px;
 
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
        <?php
        if($role == 0)
        {
        ?> 

<form method="post" action="../../API/export.php">
    <div class="row">
     <!-- project Name -->  
    <div class="col-lg-4 mb-4">
    <label for="html">Projects</label><br>
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

   

    <div class="col-xxl-4 col-md-6 mb-5">
    <label for="html">To</label><br>
    <input type="date" id="start_date" name="start_date" id="start_date" class="form-control" placeholder="start date">
    </div>

    <div class="col-xxl-4 col-md-6 mb-5">
    <label for="html">From</label><br>
    <input type="date" id="end_date" name="end_date" id="end_date" class="form-control" placeholder="end date">
    </div>


    <div class="col-lg-12 text-end mb-4">
    <button type="submit" class="btn btn-info">Export to CSV</button>
     
    </div>
    </form>

      <?php 
       }
        ?>
   

        <div class="col-lg-12">
          <div class="card">
            <div class="row">   
                <div class="col-lg-12">
                    <div class="card-body">
                        <h5 class="card-title pb-1 pt-4"> Tasks</h5>
                        <hr>
                        <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Project Name</th>
                    <th scope="col"> Employeee</th>
                    <th scope="col">Date</th> 
                    <th scope="col">Task Name</th>                                                     
                    <th scope="col">Timeframe</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    get_report($role,$eid,$db, $page, $recordsPerPage);
                  ?>                 
                </tbody>
              </table>
                    </div>
                </div>
            </div>
          </div>

        </div>
        
      </div>

      <!-- pagination -->
      <?php 
      if($role == 0){
        $sql = "SELECT COUNT(*) AS total FROM task";
      }else{
        $sql = "SELECT COUNT(*) AS total FROM task WHERE eid ='$eid'";
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 













  





