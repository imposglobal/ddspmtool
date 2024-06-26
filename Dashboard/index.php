<?php 
require('header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('sidebar.php');
?>
<?php include '../API/function.php'; 
      include '../API/operation.php'; 
$task_count = get_task_count($role, $eid, $db);
$project_count = get_project_count($role, $eid, $db);
$attendance_count = get_attendance_count($role, $eid, $db);
$totalWeeklyWorkingDays = MonthlyWorkingDays();

?>


<main id="main" class="main">
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Tasks Card -->
          <div class="col-xxl-3 col-lg-3 col-md-6">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title">Tasks <span>| This Month</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-code-square"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?php echo $task_count; ?>
                    </h6>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tasks Card -->

          <!-- Projects Card started -->
          <div class="col-xxl-3 col-lg-3 col-md-6">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">Projects <span>| This Month</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-laptop"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?php echo $project_count; ?>
                    </h6>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Project Card -->

          <!-- Working days Card  start -->
          <div class="col-xxl-3 col-lg-3 col-md-6">
            <div class="card info-card revenue-card">
              <div class="card-body">
                <h5 class="card-title">Working Days <span>| This Month</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar"></i>
                  </div>
                  <div class="ps-3">
                    <p> <?php echo  $workingDaysInfo['remainingWorkingDays'] . " Days to go"; ?>  </p>
                     <span class="text-success small pt-1 fw-bold"> <?php echo $totalWeeklyWorkingDays;?> / <?php echo "$totalMonthlyWorkingDays";?></span> 
                     <span   class="text-muted small pt-2 ps-1">Days</span>            
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End working days Card -->

          <!-- Present days Card -->
          <div class="col-xxl-3 col-lg-3 col-md-6">
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
          </div><!-- End Present days Card -->

          <!-- Recent Sales -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">
              <div class="card-body">
                <h5 class="card-title">Tasks <span>| Today</span></h5>
                <table class="table  datatable">
                  <thead>
                    <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Employee</th>
                      <th scope="col">Task Type</th>
                      <th scope="col">Title</th>
                      <th scope="col">Estimated Time</th>
                      <th scope="col">M Status</th>
                      <th scope="col">Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php get_projects_by_current_date($role, $eid, $db); ?>
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