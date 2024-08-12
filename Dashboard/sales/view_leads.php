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
 .searchfield{
 margin-left:150px;
}
 .searchbtn{
  margin-left:-81px;
 }
 .importbtn:hover
    {
      background-color: orange;
      color: #fff;
    }


    .entries_perpage{
      margin-right:20px;
    }

    .btn-empty
    { 
     background-color: #0b5ed7;
     color:#fff;
    }

    .btn-empty:hover
    {
     background-color: #0b5ed7;
     color:#fff;
    }
 .dicon
 {
  color:#fff;
  margin-right:5px;
 }

 .download_empty_excel{
    margin-left: 4px;
    margin-top: -21px;
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
      <!-- /********************** export form ******************/ -->
                    <form method="GET">
                      <div class="row">
                          <!-- Start Date -->
                          <div class="col-lg-2 mb-4">
                              <input type="date" class="form-control" name="start_date">
                          </div>
                          <!-- End Date -->
                          <div class="col-lg-2 mb-4">
                              <input type="date" class="form-control" name="end_date">
                          </div>
                            <!-- time -->
                          <div class="col-lg-2 mb-4">
                            <select class="form-select" name="time_status">
                              <option selected disabled="true">Select Time</option>
                              <option value="today">Today</option>
                              <option value="yesterday">Yesterday</option>
                              <option value="weekly">This Week</option>
                              <option value="monthly">This Month</option>
                            </select>
                          </div>
                          <!-- time-->

                          <!-- filter based on status -->
                          <div class="col-lg-2 mb-4">
                            <select class="form-select" name="client_status">
                              <option selected disabled="true">Select Status</option>
                              <option value="New Lead">New Lead</option>
                              <option value="Open">Open</option>
                              <option value="In Progress">In Progress</option>
                              <option value="Quotation Shared">Quotation Shared</option>
                              <option value="On Boarded">On Boarded</option>
                              <option value="Dropout">Dropout</option>

                            </select>
                          </div>
                          <!-- filter based on status-->

                          <!-- Buttons -->
                          <div class="col-lg-3 mb-4 text-start">
                              <button type="submit" name="show" class="btn btn-success">Show</button>
                              <button type="submit" formaction="../../API/export_leads_api.php" class="btn exportbtn ms-2">Export</button>
                          </div>
                      </div>
                    </form>
                 
              </div>


              <!-- import button -->
            <div class="col-lg-5 mt-2 mb-3">
              <form action="../../API/import_leads.php" method="POST" enctype="multipart/form-data">
              <div class="row">
                          <!-- Start Date -->
                          <div class="col-lg-9 mb-4">
                          <input  type="file" class="form-control" name="file" required>
                          </div>
                          <!-- End Date -->
                          <div class="col-lg-3 mb-4">
                            <input class="btn importbtn" type="submit" name="submit" value="Import">
                          </div>
                          <a href="../../API/empty_export_leads_api.php" class="download_empty_excel" >Download Template</a>

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
                        <!-- search bar form -->
                        <div class="col-lg-12">  
                          <form id="searchdata"  method="GET" enctype="multipart/form-data">
                            <div class="row">
                            <div class="col-lg-4">
                            <div class="d-flex flex-row">
                            <div> <h5 class="card-title pb-1 pt-4">Leads</h5></div>
                            <div class="px-2 pt-3"> 
                            <form id="searchdata" method="GET" action="../../API/function.php">
                                <select class="form-control" name="entries_per_page" id="entries_per_page">
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                    
                                </select>
                                <!-- Other form inputs here -->
                            </form>      
                            </div>
                            </div>
                            </div>

                            <div class="col-lg-8 text-end">
                            <div class="d-flex flex-row justify-content-end">
                            <div class="searchfield px-5 pt-4">
                            <input  type="text" class="form-control" name="search_data" placeholder="Search here......" required>
                            </div>
                            <div class="searchbtn px-5 pt-4">
                            <button type="submit" name="search" class="btn btn-success">Search</button>
                            </div>
                            </div>
                            </div>


                              <!-- <div class="col-lg-2 mt-2">
                                <h5 class="card-title pb-1 pt-4">Leads</h5>
                              </div>
                              <div class="col-lg-2 mt-4 entries_perpage">
                                <select class="form-control " name="entries_per_page" id="entries_per_page" >
                                
                                  <option value="10">10</option>
                                  <option value="25">25</option>
                                  <option value="75">75</option>
                                  <option value="100">100</option>
                                 </select>                            
                              </div>
                              
                              <div class="col-lg-4 mt-4 searchfield">
                                <input  type="text" class="form-control" name="search_data" placeholder="Search here......" required>
                              </div>
                              <div class="col-lg-3 mt-4 text-start searchbtn">
                                <button type="submit" name="search" class="btn btn-success">Search</button>
                              </div> -->



                            </div>
                          </form>               
                        </div>
                        <!-- search bar form -->
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
                      $page = isset($_GET['page']) ? $_GET['page'] : 1;
                      $recordsPerPage = isset($_GET['entries_per_page']) ? intval($_GET['entries_per_page']) : 10;
                      $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                      $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                      $search_data = isset($_GET['search_data']) ? $_GET['search_data'] : '';
                      $client_status = isset($_GET['client_status']) ? $_GET['client_status'] : '';

                      get_leads($base_url, $db, $page, $recordsPerPage, $start_date, $end_date);
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
// for Leads pagination 
$sql = "SELECT COUNT(*) AS total FROM sales_lead_generation WHERE 1=1";

$where_conditions = [];

if ($start_date && $end_date) {
    $where_conditions[] = "created_at BETWEEN '$start_date' AND '$end_date'";
}

/*********************** Query to search data by name ,email,mobile number  *************************/
if (!empty($search_data)) {
    $search_data = mysqli_real_escape_string($db, $search_data); // Prevent SQL injection
    $where_conditions[] = "(client_name LIKE '%$search_data%' 
                           OR email_id LIKE '%$search_data%' 
                           OR business_name LIKE '%$search_data%'
                           OR contact_number LIKE '%$search_data%')";
}

/*********************** Query to search data by status  *************************/

if (!empty($client_status)) {
  $client_status = mysqli_real_escape_string($db, $client_status); // Prevent SQL injection
  // $where_conditions[] = "(status LIKE '%$client_status%')";
  $where_conditions[] = "status = '$client_status'";
}


if (!empty($where_conditions)) {
    $sql .= " AND " . implode(" AND ", $where_conditions);
}

$entries_per_page = isset($_GET['entries_per_page']) ? intval($_GET['entries_per_page']) : 10;
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($result);
// $totalRecords = $row['total'];
// $totalPages = ceil($totalRecords / $recordsPerPage);
$totalRecords = isset($row['total']) ? $row['total'] : 0;
$totalPages = $recordsPerPage > 0 ? ceil($totalRecords / $entries_per_page) : 0;

//pagination($page, $totalPages, $search_data,$client_status);
 pagination($page, $totalPages, $search_data,$client_status,$start_date, $end_date , $entries_per_page);

?>

    </section>
  </main>
  
  <!-- End #main -->

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
<!-- script for entries per page  -->
    <!-- <script>
    document.getElementById('entries_per_page').addEventListener('change', function() {
        // Get the form element
        var form = document.getElementById('searchdata');
        
        // Create a FormData object from the form
        var formData = new FormData(form);
        
        // Add the selected value to FormData
        formData.set('entries_per_page', this.value);
        
        // Convert FormData to query string
        var queryString = new URLSearchParams(formData).toString();
        
        // Redirect to the same page with the new query string
         //window.location.href = 'https://dds.doodlo.in/Dashboard/sales/view_leads.php?' + queryString;
         window.location.href = 'http://localhost/ddspmtool/Dashboard/sales/view_leads.php?' + queryString;

});
</script> -->


<script>
document.getElementById('entries_per_page').addEventListener('change', function() {
    var form = document.getElementById('searchdata');
    var formData = new FormData(form);
    formData.set('entries_per_page', this.value);
    var queryString = new URLSearchParams(formData).toString();
    window.location.href = 'http://localhost/ddspmtool/Dashboard/sales/view_leads.php?' + queryString;
});
</script>

<!-- script for entries per page -->

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
