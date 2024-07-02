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

                    get_leads($base_url,$db, $page, $recordsPerPage);
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
