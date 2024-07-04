<?php 

require('../header.php');
?>
<title>View Leads - DDS</title>
<?php 

require('../sidebar.php');
require('../../API/sales_lead_generation_api.php');
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
    .icon{
      font-size:20px;
      margin:0 5px;
      cursor: pointer;
    }

    .custom-offcanvas {
            width: 50% !important; /* Adjust the width as needed */
        }

/* export button code */

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
 .importbutton{
  margin-left: 602px;
 }
 .importbtn{
  background-color: orange;
  color:#fff;
 }
 .importbtn:hover
    {
      background-color: orange;
      color: #fff;
    }

</style>


<main id="main" class="main">

    <div class="pagetitle">
      <h1>Leads</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">View Leads</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">

                
            <div class="row">
              <div class="col-lg-12">
      <!-- /********************************************** import form ****************************************************/ -->

                    <?php
                      // Check if import success parameter is present in URL and show popup message
                      if (isset($_GET['import_success']) && $_GET['import_success'] === '1') {
                            echo '<script>
                                    // Show popup message
                                    alert("CSV file has been successfully imported.");

                                    // Remove import_success parameter from URL
                                    const urlWithoutParams = window.location.href.split("?")[0];
                                    history.replaceState(null, null, urlWithoutParams);
                                  </script>';
                        }
                    ?>

                  <div class="mb-4 importbutton">
                    <form action="../../API/import_leads.php" method="POST" enctype="multipart/form-data">
                      <input  type="file" name="file" required>
                      <input class="btn importbtn ms-2" type="submit" name="submit" value="Import">
                    </form> 
                  </div>
                  
      <!-- /********************************************** export form ****************************************************/ -->

                  <form method="GET">
                      <div class="row">
                          <!-- Start Date -->
                          <div class="col-lg-3 mb-4">
                              <input type="date" class="form-control" name="start_date">
                          </div>
                          <!-- End Date -->
                          <div class="col-lg-3 mb-4">
                              <input type="date" class="form-control" name="end_date">
                          </div>
                          <!-- time -->
          <div class="col-lg-3 mb-4">
            <select class="form-select" name="time_status">
              <option selected disabled="true">Select Time</option>
              <option value="today">Today</option>
              <option value="yesterday">Yesterday</option>
              <option value="weekly">This Week</option>
              <option value="monthly">This Month</option>
            </select>
          </div>
          <!-- time-->
                          <!-- Buttons -->
                         
                          <div class="col-lg-3 mb-4 text-start">
                              <button type="submit" name="show" class="btn btn-success">Show</button>

                              <button type="submit" formaction="../../API/export_leads_api.php" class="btn exportbtn ms-2">Export</button>
                          </div>
                      </div>
                  </form>
                 
              </div>
          </div>


      <!-- /*************************************************** export form ends ***********************************************************/ -->
        <div class="col-lg-12">
          <div class="card">
            <div class="row">   
                <div class="col-lg-12">
                    <div class="card-body">
                        <h5 class="card-title pb-1 pt-4">Leads</h5>
                        <hr>
                        <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Lead Date</th>
                    <th scope="col">Client Name </th>
                    <th scope="col">Business Name</th>
                    <th scope="col">Email ID</th>
                    <th scope="col">Contact Number</th>
                    <th scope="col">Looking For?</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                    

                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

                    get_leads($base_url,$db, $page, $recordsPerPage, $startDate, $endDate);
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
      //for Leads pagination 
      $sql = "SELECT COUNT(*) AS total FROM sales_lead_generation";
      if ($startDate && $endDate) {
        $sql .= " WHERE created_at BETWEEN '$startDate' AND '$endDate'";
    }
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_assoc($result);
      $totalRecords = $row['total'];
      $totalPages = ceil($totalRecords / $recordsPerPage);
      
      pagination($page, $totalPages);
      ?>






    </section>

  </main><!-- End #main -->



  <!-- /*************************************************** drawer code ********************************************************/  -->


            <!-- start of drawer -->
             <div class="offcanvas offcanvas-end custom-offcanvas" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
              <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">View Lead</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <!-- form code for drawer is in the get_lead_details.php API file --> 
               
                     
                </div>
                    
            </div>
            
               <!-- end of drawer -->
            
            <!-- /*************************************************** drawer code end ********************************************************************/ -->
            


  <script>
function openLeadDrawer(lead_id) {
    // Make an AJAX request to fetch the lead details
    $.ajax({
        url: "../../API/get_lead_details.php",
        type: "GET",
        data: { lead_id: lead_id },
        success: function(response) {
            // Populate the offcanvas drawer with the response data
            $('#offcanvasRight .offcanvas-body').html(response);

            // Open the offcanvas drawer
            var offcanvasElement = document.getElementById('offcanvasRight');
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
            bsOffcanvas.show();

             // Redirect to the same page with lead_id in the URL
             //window.location.href = '../../Dashboard/sales/view_leads.php?lead_id=' + lead_id;
        },
        error: function(xhr, status, error) {
            console.error('Error fetching lead details:', error);
        }
    });
}
</script>




<!-- code to delete users -->
<script>

// Code to delete the employee
function deleteLead(lead_id) {
    Swal.fire({
        title: 'Are you sure to delete this record ?',
        text: 'You are about to delete this user. This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with deletion
            $.ajax({
                url: "../../API/delete_lead.php",
                type: 'POST',
                data: { ops: 'deleteLead', lead_id: lead_id }, 
                success: function (response) {
                    if (response === "true") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Record deleted successfully!'
                        }).then(function() {
                            // Reload the page after successful deletion
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting user: ' + error
                    });
                }
            });
        }
    });
}



</script>

 


<?php 
require('../footer.php');
?>
