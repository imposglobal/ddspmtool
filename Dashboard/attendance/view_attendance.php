<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
?>
<?php include '../../API/attendance.php'; 
      include '../../API/operation.php'; 
      include '../../API/function.php'; 
?>

<!-- Check whether the 'get_date' parameter is set or not. -->
<?php
$start_date = null;
$end_date = null;

if(isset($_GET["get_date"])) 
{
    if(isset($_GET["start_date"]) && isset($_GET["end_date"])) 
    {
        $start_date = $_GET["start_date"];
        $end_date = $_GET["end_date"];
    }
    else{
        $start_date = null;
        $end_date = null;
    }
}


 ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Attendance</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">Home</a></li>
        <li class="breadcrumb-item active">Attendance</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
      <form method="GET" action="view_attendance.php">
    <div class="row">
 
          <div class="col-xxl-3 col-md-6 mb-5">
          <label for="html">From</label><br>
          <input type="date" id="start_date" name="start_date" class="form-control" placeholder="start date">
          </div>
          <!-- End Tasks Card -->

          <!-- Projects Card started -->
          <div class="col-xxl-3 col-md-6 mb-5">
          <label for="html">To</label><br>
          <input type="date" id="end_date" name="end_date" class="form-control" placeholder="End date">
          </div>
          <!-- End Project Card -->

          <!-- Projects Card started -->
          <div class="col-xxl-3 col-md-6 mb-5">
          <button type="submit" class="btn mt-4" name="get_date"  style="background-color: #012970;color:#fff;">Submit</button>
          </div>
          <!-- End Project Card -->  
         
    </div>
     </form>
  </section>

  <section class="section">
      <div class="row">
       
        <div class="col-lg-12">
          <div class="card">
            <div class="row">   
                <div class="col-lg-12">
                    <div class="card-body">
                    <h5 class="card-title">Attendance <span>| Today</span></h5>
                        <hr>
                        <table class="table">
                <thead>
                <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Employee Name </th>
                      <th scope="col">Date</th>
                      <th scope="col">Login Time</th>            
                      <th scope="col">Logout Time</th>                   
                    </tr>
                </thead>
                <tbody>
                <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    get_attendance($role,$eid,$db, $page, $recordsPerPage,  $start_date, $end_date);
                  ?> 
                </tbody>
              </table>
                    </div>
                </div>
            </div>
          </div>

        </div>
        <?php 
      $sql = "SELECT COUNT(*) AS total FROM attendance";
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_assoc($result);
      $totalRecords = $row['total'];
      $totalPages = ceil($totalRecords / $recordsPerPage);      
      pagination($page, $totalPages);
      ?>        
      </div>
    </section>



</main><!-- End #main -->
<?php 
require('../footer.php');

?>
