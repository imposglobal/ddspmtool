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
</style>

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
      <div class="row">
       
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
                    <th scope="col"> Employeee</th>
                    <th scope="col">Task Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Timeframe</th>
                    <th scope="col">My Status</th>
                    <th scope="col">Manager Status</th>
                    <th scope="col">View Details</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    get_tasks($role,$eid,$db, $page, $recordsPerPage);
                  ?> 
                </tbody>
              </table>
                    </div>
                </div>
            </div>
          </div>

        </div>
        
      </div>
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
