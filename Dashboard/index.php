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
// $date = date("Y-m-d");

?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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
          <div class="col-xxl-3 col-md-6">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title">Tasks <span>| Today</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-cart"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?php echo $task_count; ?>
                    </h6>
                    <span class="text-success small pt-1 fw-bold">12%</span> 
                    <span class="text-muted small pt-2 ps-1">increase</span>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tasks Card -->

          <!-- Projects Card started -->
          <div class="col-xxl-3 col-xl-12">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">Projects <span>| Till Now</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?php echo $project_count; ?>
                    </h6>
                    <span class="text-danger small pt-1 fw-bold">12%</span> <span
                      class="text-muted small pt-2 ps-1">decrease</span>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Project Card -->

          <!-- Working days Card  start -->
          <div class="col-xxl-3 col-md-6">
            <div class="card info-card revenue-card">
              <div class="card-body">
                <h5 class="card-title">Working Days <span>| This Month</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6> <?php echo "$totalWeeklyWorkingDays";?> </h6>
                    <span class="text-success small pt-1 fw-bold"><?php echo "$totalMonthlyWorkingDays";?></span> <span
                      class="text-muted small pt-2 ps-1">Days</span>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End working days Card -->

          <!-- Present days Card -->
          <div class="col-xxl-3 col-md-6">
            <div class="card info-card revenue-card">
              <div class="card-body">
                <h5 class="card-title">Present Days <span>| This Month</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6>$3,264</h6>
                    <span class="text-success small pt-1 fw-bold">8%</span> <span
                      class="text-muted small pt-2 ps-1">increase</span>
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
                <table class="table table-borderless datatable">
                  <thead>
                    <tr>
                      <th scope="col">tid</th>
                      <th scope="col">task_type</th>
                      <th scope="col">title</th>
                      <th scope="col">timeframe</th>
                      <th scope="col">m_status</th>
                      <th scope="col">created_at</th>
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