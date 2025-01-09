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
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/client/dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Projects Analytics</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
    <div class="col-lg-12">
    <form method="GET">
    <div class="row">

  
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
                    <th scope="col">Total Projects Time </th> 
                    <th scope="col">Total Break Time </th>
                    <th scope="col">Total Productive Time</th>                  
                   
                                           
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    get_task_analytics_client($db, $cid, $page, $recordsPerPage);
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
     
     
        $sql = "SELECT COUNT(*) AS total FROM projects WHERE cid ='$cid'";
 
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_assoc($result);
      $totalRecords = $row['total'];
      $totalPages = ceil($totalRecords / $recordsPerPage);
      
      pagination($page, $totalPages);
      ?>
    </section>

  </main>
 
<?php 
require('../footer.php');
?>
