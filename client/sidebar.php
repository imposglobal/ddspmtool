<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

<!-- /******************************************* Dashboard ********************************************** */ -->

          <li class="nav-item">
            <a class="nav-link collapsed" href="<?php echo $base_url;?>/client/dashboard.php">
              <i class="bi bi-grid"></i>
              <span>Dashboard</span>
            </a>
          </li>
 
<!-- /******************************************* Dashboard End ********************************************** */ -->


<!-- /***************************************** Sales Start *****************************************************************/ -->

<!-- Sales managment section start-->

 
<!-- /*************************************** Sales End *************************************************************/ -->



<!-- /******************************************* Tasks Start ********************************************** */ -->

<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-file-earmark-text-fill"></i><span>Tasks</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
      <li>
        <a href="<?php echo $base_url;?>/client/analytics/task_analytics.php">
          <i class="bi bi-circle"></i><span>Task Analytics</span>
        </a>
      </li>
     
    
    </ul>
  </li>

<!-- /******************************************* Tasks End ********************************************** */ -->
 

<!-- /**************************************************** Operations Start ******************************************** */ -->


  
 <hr/>

 
 <!-- Reports -->

  
  <!-- <li class="nav-heading">Accounts</li>
  <li class="nav-item">
    <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/employee/user-profile.php">
      <i class="bi bi-person"></i>
      <span>Profile</span>
    </a>
  </li> -->
  <!-- End Profile Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="<?php echo $base_url;?>/client/logout.php">
      <i class="bi bi-box-arrow-in-left"></i>
      <span>Sign Out</span>
    </a>
  </li><!-- End Login Page Nav -->
</ul>

</aside><!-- End Sidebar-->
