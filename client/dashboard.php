<?php 
require('header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('sidebar.php');
?>
<?php include '../API/function.php'; 
      include '../API/operation.php'; 
/**$task_count = get_task_count($role, $eid, $db);
$project_count = get_project_count($role, $eid, $db);
$attendance_count = get_attendance_count($role, $eid, $db);
$in_progress_count = get_in_progress_task_count($role, $eid, $db);
$totalWeeklyWorkingDays = MonthlyWorkingDays(); **/

?>


<main id="main" class="main">
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">


          <!-- Present days Card -->
          <!-- <div class="col-xxl-3 col-lg-3 col-md-6">
            <div class="card info-card revenue-card">
              <div class="card-body">
                <h5 class="card-title">Present Days <span>| This Month</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar-check"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $attendance_count; ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
          <!-- End Present days Card -->
           
          <!-- Recent Sales -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">
              <div class="card-body">
                <h5 class="card-title">Tasks <span>| Today</span></h5>
                <table class="table  datatable">
                  <thead>
                    <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Date</th>
                      <th scope="col">Employee</th>
                      <th scope="col">Title</th>
                      <th scope="col">Project Name</th>
                      <th scope="col">Estimated Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php get_projects_by_client( $cid, $db); ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div><!-- End Recent Sales -->
        </div>
      </div><!-- End Left side columns -->
    </div>
  </section>


</main><!-- End #main -->

<?php 
require('footer.php');
?>