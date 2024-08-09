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

    <!-- filter -->
    <section class="section">
        <form method="GET">
            <div class="row">
                <!-- status -->
                <div class="col-lg-4 mb-4">  
                    <select class="form-select" name="time_status">
                        <option selected>Select Time</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="weekly">This Week</option>
                        <option value="monthly">This Month</option> 
                    </select>
                </div>
                <!-- status -->

                <!-- start date -->
                <div class="col-lg-3 mb-4">  
                    <input type="date" class="form-control" name="start_date">
                </div>
                <!-- start date -->

                <!-- end date -->
                <div class="col-lg-3 mb-4">  
                    <input type="date" class="form-control" name="end_date">
                </div>
                <!-- end date -->

                <div class="col-lg-2 text-start">
                    <button type="submit" name="show" class="btn btn-success">Show</button> 
                    <button type="submit" formaction="../../API/export_clients.php" class="btn exportbtn ms-2">Export</button>  
                </div>
            </div>
        </form>
    </section>

    <!-- filter -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row">   
                        <div class="col-lg-12">
                            <div class="card-body">

                                <!-- search -->
                                <div class="col-lg-12">  
                                    <form id="searchdata" method="GET">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <h5 class="card-title pb-1 pt-4">Client</h5>
                                            </div>

                                            <div class="col-lg-8 text-end">
                                                <div class="d-flex flex-row justify-content-end">
                                                    <div class="searchfield px-3 pt-4">
                                                        <input type="text" class="form-control" name="search_data" placeholder="Search here......" required>
                                                    </div>
                                                    <div class="searchbtn pt-4">
                                                        <button type="submit" name="search" class="btn btn-success">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>               
                                </div>
                                <!-- search -->                   
                                <hr>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Client</th>
                                            <th scope="col">Business Name</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            // Usage:
                                            $page = isset($_GET['page']) ? $_GET['page'] : 1;                   
                                            $recordsPerPage = 10;
                                            $startDate = isset($_GET['start_date']) ? mysqli_real_escape_string($db, $_GET['start_date']) : '';
                                            $endDate = isset($_GET['end_date']) ? mysqli_real_escape_string($db, $_GET['end_date']) : '';
                                            $search_data = isset($_GET['search_data']) ? mysqli_real_escape_string($db, $_GET['search_data']) : '';

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
            $sql = "SELECT COUNT(*) AS total FROM clients WHERE 1=1";

            $where_conditions = [];

            if ($startDate && $endDate) {
                $where_conditions[] = "created_at BETWEEN '$startDate' AND '$endDate'";
            }
    
            /*********************** Query to search data by name, email, mobile number *************************/
            if (!empty($search_data)) {
                $search_data = mysqli_real_escape_string($db, $search_data); // Prevent SQL injection
                $where_conditions[] = "(client_name LIKE '%$search_data%' 
                                       OR business_name LIKE '%$search_data%' 
                                       OR industry LIKE '%$search_data%')";
            }

            if (!empty($where_conditions)) {
                $sql .= " AND " . implode(" AND ", $where_conditions);
            }

            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_assoc($result);
            $totalRecords = isset($row['total']) ? $row['total'] : 0;
            $totalPages = $recordsPerPage > 0 ? ceil($totalRecords / $recordsPerPage) : 0;

            pagination($page, $totalPages, $search_data);
        ?>
    </section>
</main>

  <!-- End #main -->

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
