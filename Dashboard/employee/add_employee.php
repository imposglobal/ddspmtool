<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Add Employee - DDS PM Tool</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../../assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="../../assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">DDS</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Add Employee</h5>
                    <p class="text-center small">Enter personal details to create account</p>
                    <hr>
                  </div>

                  <form id="regform" class="row mt-2 g-3 needs-validation" novalidate>
                  <div class="col-12">
                      <label for="yourName" class="form-label">Employee ID</label>
                      <input type="text" name="empid" class="form-control" id="empid" required>
                      <div class="invalid-feedback">Please, enter your Employee ID!</div>
                    </div>
                    <div class="col-6">
                      <label for="yourName" class="form-label"> First Name</label>
                      <input type="text" name="fname" class="form-control" id="fname" required>
                      <div class="invalid-feedback">Please, enter your first name!</div>
                    </div>
                    <div class="col-6">
                      <label for="yourName" class="form-label">Last Name</label>
                      <input type="text" name="lname" class="form-control" id="lname" required>
                      <div class="invalid-feedback">Please, enter your last name!</div>
                    </div>

                    <div class="col-6">
                      <label for="yourEmail" class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" id="email" required>
                      <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                    </div>
                    <div class="col-6">
                      <label for="yourphone" class="form-label">Phone</label>
                      <input type="email" name="text" class="form-control" id="phone" required>
                      <div class="invalid-feedback">Please enter a valid Phone Number</div>
                    </div>

                    <div class="col-6">
                     <label for="validationCustom04" class="form-label">Department</label>
                     <select class="form-select" id="department" required="">
                        <option selected="" disabled="" value="">Choose...</option>
                        <option value="Design">Design</option>
                        <option value="IT">IT</option>
                     </select>
                     <div class="invalid-feedback">
                        Please select a valid Designation.
                     </div>
                    </div>

                    <div class="col-6">
                     <label for="validationCustom04" class="form-label">Designation</label>
                     <select class="form-select" id="designation" required="">
                        <option selected="" disabled="" value="">Choose...</option>
                        <option value="Sr. Fullstack Developer">Sr. Fullstack Developer</option>
                        <option value="Jr. Fullstack Developer">Jr. Fullstack Developer</option>
                        <option value="Jr. Web Developer">Jr. Web Developer</option>
                        <option value="Web Developer Intern">Web Developer Intern</option>
                        <option value="Jr. Graphics Designer">Jr. Graphics Designer</option>
                        <option value="Intermediate Graphics Designer">Intermediate Graphics Designer</option>
                        <option value="Illustrator Designer">Illustrator Designer</option>
                        <option value="Design Intern">Design Intern</option>
                     </select>
                     <div class="invalid-feedback">
                        Please select a valid Designation.
                     </div>
                    </div>

                    

                   
                    <div class="col-12 mt-5">
                      <button id="saveBtn" class="btn btn-primary py-2 w-100" type="submit">Create Account</button>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

 <!-- Vendor JS Files -->
 <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../../assets/vendor/quill/quill.min.js"></script>
  <script src="../../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../assets/vendor/php-email-form/validate.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert library -->

  <!-- Template Main JS File -->
  <script src="../../assets/js/main.js"></script>
  <script>
   // Function to handle form submission with jQuery AJAX
$(document).ready(function() {
    $('#saveBtn').click(function(e) {
        e.preventDefault();
        var empid = $('#empid').val(); // Trim whitespace
        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var department = $('#department').val();
        var designation = $('#designation').val();

        if(empid !== "" && fname !== "" && lname !== "" && email !== "" && phone !== "" && department !== "" && designation !== "") {
            // AJAX request
            $.ajax({
                type: "POST",
                url: "../../API/insert.php",
                data: { ops: 'register', empid: empid, fname: fname, lname:lname, email:email, phone:phone, department:department, designation:designation},
                success: function(response) {
                    // Use SweetAlert for displaying success message
                    Swal.fire({
                        icon: 'success',
                        title: fname + " " + lname,
                        text: response
                    });
                    // Reset the form
                    $('#regform').trigger('reset');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Use SweetAlert for displaying error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again later.'
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Required!',
                text: "Please fill all the fields"
            });
        }
    });
});
</script>

</body>

</html>