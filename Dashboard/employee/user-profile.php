<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');
require("../../API/db.php");
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
</style>

<?php
if(isset($_GET['eid'])){
  $eid = $_GET['eid'];
}

  $sql = "SELECT * FROM employees WHERE eid = '$eid'";
  $result = mysqli_query($db, $sql);

$result = mysqli_query($db, $sql);
if ($result && mysqli_num_rows($result) > 0)
{
  while ($row = mysqli_fetch_assoc($result))
  {
    ?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

             
            <h2><?php echo $row["fname"] . ' ' . $row["lname"]; ?></h2>
              <h5 class="mt-2"><?php echo $row["designation"];?></h5>
             
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Profile Details</h5>
                  
      
 
                  <div class="row">
                  
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo $row["fname"] . ' ' . $row["lname"]; ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Designation</div>
                    <div class="col-lg-9 col-md-8"><?php echo $row["designation"];?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?php echo $row["email"];?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Phone</div>
                    <div class="col-lg-9 col-md-8"><?php echo $row["phone"];?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Deparment</div>
                    <div class="col-lg-9 col-md-8"><?php echo $row["department"];?></div>
                  </div>
                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form method="POST">
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="fname" type="text" class="form-control" id="fname" value="<?php echo $row["fname"];?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="lname" type="text" class="form-control" id="lname" value="<?php echo $row["lname"];?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Designation</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="designation" type="text" class="form-control" id="designation" value="<?php echo $row["designation"];?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="text" class="form-control" id="email" value="<?php echo $row["email"];?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="phone" type="text" class="form-control" id="phone" value="<?php echo $row["phone"];?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Department</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="department" type="text" class="form-control" id="department" value="<?php echo $row["department"];?>">
                      </div>
                    </div>

                    <?php }
}
?>
                   
                    <div class="text-center">  
                    <!-- <input type="hidden" class="form-control" name="eid" id="eid" value="<?php echo $row["eid"];?>"> -->

                    <input type="hidden" id="eid" value="<?php echo $eid; ?>" class="form-control">
                   
                    <input type="button" class="btn btn-primary" name="update" id="update" value="Save Changes">
                    </div>
                  </form>
                  <!-- End Profile Edit Form -->
                </div>
                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->

                    <form method="POST" id="changePasswordForm">
                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>
                   
                    <div class="text-center"> 
                       <input type="hidden" id="eid" value="<?php echo $eid; ?>" class="form-control">
                      <input type="button" class="btn btn-primary"  id="update_password" value="Change Password">
                    </div>
                  </form>
                  <div id="message"></div>
                  <!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

<?php 
require('../footer.php');
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Include SweetAlert library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<!-- code for edit employee profile -->
<script>
    $(document).ready(function (e) {
        $('#update').click(function (e) {
          // e.preventDefault();
        var eid = $('#eid').val(); 
        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var department = $('#department').val();
        var designation = $('#designation').val();
            $.ajax({
               url: "../../API/update.php",
                type: 'POST',
                data:{ ops: 'update_profile', eid: eid, fname: fname, lname:lname, email:email, phone:phone, department:department, designation:designation},
                success: function (response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Show SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message
                        });
                    } else {
                        // Show SweetAlert error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Show error message if AJAX request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating profile: ' + error
                    });
                }
            });
        });
    });
</script>




<!-- code for change password -->
<script>
  $(document).ready(function() {
    $('#update_password').click(function() {
        var newPassword = $('#newPassword').val();
        var renewPassword = $('#renewPassword').val();
        var eid = $('#eid').val();

        if (newPassword != renewPassword) {
          Swal.fire({
        icon: 'error',
        title: 'Passwords do not match!',
        text: 'Please make sure the passwords match.'
        });
       return;
        }

        $.ajax({
            type: 'POST',
            url: '../../API/update.php',
            data: { ops: 'update_password', eid: eid, newpassword: newPassword, renewpassword: renewPassword },


            success: function (response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Show SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message
                        });
                    } else {
                        // Show SweetAlert error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Show error message if AJAX request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating profile: ' + error
                    });
                }
        });
    });
});

</script>


