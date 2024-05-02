<?php 
$session = session_start();
// Check if the user is logged in
if(isset($_SESSION['username'])) {
    $name = $_SESSION['name'];
    $desgn = $_SESSION['design'];
    $eid = $_SESSION['eid'];
    $role = $_SESSION['role'];
    $base_url="https://".$_SERVER['SERVER_NAME'];
    if($base_url == "https://dds.doodlo.in/"){
      $base_url="https://".$_SERVER['SERVER_NAME'];
    }else{
      $base_url="https://".$_SERVER['SERVER_NAME']."/ddspmtool";
    }

} else {
    // Redirect user to the login page if not logged in
    header("Location: http://dds.doodlo.in/index.php");
    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="https://dds.doodlo.in/assets/img/favicon.png" rel="icon">
  <link href="https://dds.doodlo.in/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="https://dds.doodlo.in/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://dds.doodlo.in/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="https://dds.doodlo.in/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="https://dds.doodlo.in/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="https://dds.doodlo.in/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="https://dds.doodlo.in/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="https://dds.doodlo.in/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="https://dds.doodlo.in/assets/css/style.css" rel="stylesheet">
  <style>
    #clockout{
      display:none;
    }

    .datatable-wrapper.no-footer .datatable-container {
    border-bottom: none;
    }
  </style>

 
</head>

<body>
   <!-- ======= Header ======= -->
   <header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="<?php echo $base_url;?>/Dashboard/index.php" class="logo d-flex align-items-center">
    <img src="https://dds.doodlo.in/assets/img/logo.png" alt="">
    <span class="d-none d-lg-block">DDS</span>
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<div id="clockin"  class="search-bar">
<a href="#" class="btn btn-success mx-3 px-5" onclick="clockin();">Clock In</a>
</div><!-- End Search Bar -->
<div id="clockout" class="search-bar">
<a href="#" class="btn btn-danger mx-3 px-5" onclick="clockout();">Clock Out</a>
</div><!-- End Search Bar -->

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

    <li class="nav-item d-block d-lg-none">
      <a class="nav-link nav-icon search-bar-toggle " href="#">
        <i class="bi bi-search"></i>
      </a>
    </li><!-- End Search Icon-->

  

<!-- End Messages Nav -->
    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
      <i class="bi bi-person-circle"></i>
        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo "$name"; ?></span>
      </a><!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
        <i class="bi bi-person-circle"></i>
          <h6><?php echo "$name"; ?></h6>
          <span><?php echo "$desgn"; ?></span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo $base_url;?>/Dashboard/employee/user-profile.php">
            <i class="bi bi-gear"></i>
            <span>Account Settings</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="<?php echo $base_url;?>/Dashboard/logout.php"">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
          </a>
        </li>

      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->