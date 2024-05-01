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
                <h5 class="card-title">Tasks <span>| Today</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-cart"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?php echo $task_count; ?>
                    </h6>
                    <!-- <span class="text-success small pt-1 fw-bold">12%</span> 
                    <span class="text-muted small pt-2 ps-1">increase</span> -->
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Tasks Card -->

          <!-- Projects Card started -->
          <div class="col-xxl-3 col-lg-3 col-md-6">
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
                    <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span
                      class="text-muted small pt-2 ps-1">decrease</span> -->
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
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6> <?php echo $totalWeeklyWorkingDays;?> </h6>
                     <span class="text-success small pt-1 fw-bold"><?php echo "$totalMonthlyWorkingDays";?></span> 
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
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $attendance_count; ?></h6>
                    <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span
                      class="text-muted small pt-2 ps-1">increase</span> -->
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
<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>DDS</span></strong>. All Rights Reserved
    </div>
   
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="https://dds.doodlo.in/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/echarts/echarts.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/quill/quill.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/php-email-form/validate.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert library -->


  <!-- Template Main JS File -->
  <script src="https://dds.doodlo.in/assets/js/main.js"></script>
<script>
  $(document).ready(function(){
    $('#clockout').hide();
    // Function to check data and hide elements
    function checkAndHide() {
        $.ajax({
            url: "<?php echo $base_url; ?>/API/attendance.php",
            method: "POST",
            data: { ops: 'checkatt', eid:<?php echo $eid; ?> },
            success: function(response) {
                // Assuming data is retrieved successfully
                // You may need to modify the condition based on your data
                if (response === 'clockin') {
                    $('#clockin').hide(); // Hide elements with class 'jquy'
                    $('#clockout').show();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }

    // Call the function on document load
    checkAndHide();
});
</script>
  <!-- Ajax Request for Clockin -->
<script>
  function clockin() 
  {
      var eid = '<?php echo $eid ?>';    
      $.ajax({
      type: 'POST', 
      url: '../API/attendance.php', 
      data: 
      { 
        ops: 'clockin', 
        eid: eid
      },
      success: function(response) { 
        console.log('Data sent successfully!');
        window.location.reload();
      },
      error: function(xhr, status, error) { 
        console.error('Error occurred while sending data:', error);
      }
    });
  }
</script>

<!-- Ajax Request for Clockout -->

<script>
  function clockout() {
    var eid = '<?php echo $eid ?>';    
    // AJAX request
      $.ajax({
      type: 'POST', 
      url: '../API/attendance.php', 
      data: { 
        ops: 'clockout', 
        eid: eid
      },
      success: function(response) { 
        console.log('Data sent successfully!');
        window.location.reload();
      },
      error: function(xhr, status, error) { 
        console.error('Error occurred while sending data:', error);
      }
    });
  }
</script>







</body>

</html>
<?php 
require('footer.php');
?>