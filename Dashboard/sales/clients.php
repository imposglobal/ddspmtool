<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/get_clients.php');
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
</style>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Clients</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">Clients</li>
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
                        <h5 class="card-title pb-1 pt-4"> Clients</h5>
                        <hr>
                        <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Client</th>
                    <th scope="col">Buisness Name</th>
                    <th scope="col">Industry</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;

                    get_clients($db, $page, $recordsPerPage);
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
      //for project pagination 

      $sql = "SELECT COUNT(*) AS total FROM clients";
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_assoc($result);
      $totalRecords = $row['total'];
      $totalPages = ceil($totalRecords / $recordsPerPage);
      
      pagination($page, $totalPages);
      ?>
    </section>

  </main><!-- End #main -->

<script>  // Code to delete the Project
function deleteClient(cid, business_name) {
    Swal.fire({
        title: 'Are you sure to delete this client ?',
        text: 'You are about to delete this client. This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with deletion
            $.ajax({
                url: "../../API/delete.php",
                type: 'POST',
                data: { ops: 'deleteClient', cid: cid, business_name: business_name}, 
                success: function (response) {
                    if (response === "true") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Client "' + business_name + '" deleted successfully!',
                            text: ''
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
