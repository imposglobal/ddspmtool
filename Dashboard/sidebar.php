<!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

  <!-- /******************************************* Dashboard ********************************************** */ -->

              <li class="nav-item">
                <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/index.php">
                  <i class="bi bi-grid"></i>
                  <span>Dashboard</span>
                </a>
              </li>
     
  <!-- /******************************************* Dashboard End ********************************************** */ -->
  <?php if($role == 0){ ?>         
     <hr/>
     <?php }?>

<!-- /***************************************** Sales Start *****************************************************************/ -->

  <!-- Sales managment section start-->
  <?php if($role == 0){ ?>
      <li class="nav-heading">Sales</li>
    
      <!-- add leads  -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/sales/add_leads.php">
          <i class="bi bi-file-plus-fill"></i>
          <span>Add Leads</span>
        </a>
      </li>
      <!-- End addleads Page Nav -->

      <!-- View leads -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/sales/view_leads.php">
          <i class="bi bi-person"></i>
          <span>View Leads</span>
        </a>
      </li>
      <!-- End view leads Nav -->
      
      

       <!-- View leads -->
       <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/sales/clients.php">
          <i class="bi bi-person"></i>
          <span>Clients</span>
        </a>
      </li><!-- End view leads Nav -->
      <hr/>
      <?php } ?>
     
<!-- /*************************************** Sales End *************************************************************/ -->

   
<!-- /***************************************** Sales Start *****************************************************************/ -->

  <!-- Sales managment section start-->
  <?php if($role == 0){ ?>
      <li class="nav-heading">Leads</li>
    
      
      <!-- View leads -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/websiteleads/view_website_leads.php">
          <i class="bi bi-person"></i>
          <span>Website Leads</span>
        </a>
      </li>
      <!-- End view leads Nav -->
      
      <hr/>
      <?php } ?>
     
<!-- /*************************************** Sales End *************************************************************/ -->



  <!-- /******************************************* Tasks Start ********************************************** */ -->
  <?php if($role == 1) {?>   
  <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-file-earmark-text-fill"></i><span>Tasks</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?php echo $base_url;?>/Dashboard/task/tasks.php">
              <i class="bi bi-circle"></i><span>Add Task</span>
            </a>
          </li>
          <?php if($role == 0){ ?>
          <li>
            <a href="<?php echo $base_url;?>/Dashboard/task/assign_task.php">
              <i class="bi bi-circle"></i><span>Assign Tasks</span>
            </a>
          </li>
          <?php } ?>
          <li>
            <a href="<?php echo $base_url;?>/Dashboard/task/view_task.php">
              <i class="bi bi-circle"></i><span>View Tasks</span>
            </a>
          </li>
        </ul>
      </li>
<?php } ?>
<!-- /******************************************* Tasks End ********************************************** */ -->
     <?php if($role == 1){ ?>         
          <hr/>
     <?php }?>
  
<!-- /**************************************************** Operations Start ******************************************** */ -->


      <?php if($role == 0){ ?>
      <li class="nav-heading">Operations</li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#projects" data-bs-toggle="collapse" href="#">
          <i class="bi bi-collection-fill"></i><span>Project</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="projects" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
             <a href="<?php echo $base_url;?>/Dashboard/project/projects.php">
              <i class="bi bi-circle"></i><span>Add Project</span>
            </a>
          </li>
          <li>
            <a href="<?php echo $base_url;?>/Dashboard/project/assign_project.php">
              <i class="bi bi-circle"></i><span>Assign Project</span>
            </a>
          </li>
          <li>
            <a href="<?php echo $base_url;?>/Dashboard/project/view_projects.php">
              <i class="bi bi-circle"></i><span>View Projects</span>
            </a>
          </li>
        </ul>
      </li>

      <!-- /******************* Taks for role 0 ******************* */ -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-file-earmark-text-fill"></i><span>Tasks</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?php echo $base_url;?>/Dashboard/task/tasks.php">
              <i class="bi bi-circle"></i><span>Add Task</span>
            </a>
          </li>
          
          <li>
            <a href="<?php echo $base_url;?>/Dashboard/task/assign_task.php">
              <i class="bi bi-circle"></i><span>Assign Tasks</span>
            </a>
          </li>
          
          <li>
            <a href="<?php echo $base_url;?>/Dashboard/task/view_task.php">
              <i class="bi bi-circle"></i><span>View Tasks</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- /******************* Taks for role 0 ******************* */ -->

      <hr/>
      <?php } ?>

<!-- /**************************************************** Operations End ******************************************** */ -->
      
<!-- /**************************************************** Employee Start ******************************************** */ -->


<?php if($role == 0){ ?>
      <li class="nav-heading">Employee</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/employee/add_employee.php">
          <i class="bi bi-person-plus-fill"></i>
          <span>Add Employee</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/employee/view_employee.php">
          <i class="bi bi-person-circle"></i>
          <span>View Employee</span>
        </a>
      </li>
      <hr/>
      <?php } ?>

<!-- /**************************************************** Employee End ******************************************** */ -->
      

     <!-- Reports -->
     <?php if($role == 0){ ?>
      <li class="nav-heading">Reports</li>
         

      <!-- task analytics -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/analytics/task_analytics.php">
          <i class="bi bi-person"></i>
          <span>Task Analytics</span>
        </a>
      </li><!-- End Profile Page Nav -->
      
      

      <?php } ?>

       <!-- attendance  -->
       <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/attendance/view_attendance.php">
          <i class="bi bi-calendar2-week-fill"></i>
          <span>Attendance</span>
        </a>
      </li><!-- End Attendance Page Nav -->

     <hr/>

     
     <!-- Reports -->

      
      <li class="nav-heading">Accounts</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/employee/user-profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="<?php echo $base_url;?>/Dashboard/logout.php">
          <i class="bi bi-box-arrow-in-left"></i>
          <span>Sign Out</span>
        </a>
      </li><!-- End Login Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->
