<!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-file-earmark-text-fill"></i><span>Tasks</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="../task/tasks.php">
              <i class="bi bi-circle"></i><span>Add Task</span>
            </a>
          </li>
          <li>
            <a href="../task/view_task.php">
              <i class="bi bi-circle"></i><span>View Tasks</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-bar-chart-fill"></i>
          <span>Analytics</span>
        </a>
      </li><!-- End Analytics Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-calendar2-week-fill"></i>
          <span>Attendance</span>
        </a>
      </li><!-- End Analytics Page Nav -->
      <hr/>

      <?php if($role == 0){ ?>
      <li class="nav-heading">Operations</li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#projects" data-bs-toggle="collapse" href="#">
          <i class="bi bi-collection-fill"></i><span>Project</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="projects" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="../project/projects.php">
              <i class="bi bi-circle"></i><span>Add Project</span>
            </a>
          </li>
          <li>
            <a href="../project/view_projects.php">
              <i class="bi bi-circle"></i><span>View Projects</span>
            </a>
          </li>
        </ul>
      </li>
      
      <li class="nav-item">
        <a class="nav-link collapsed" href="../employee/add_employee.php">
          <i class="bi bi-person-plus-fill"></i>
          <span>Add Employee</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="../employee/view_employee.php">
          <i class="bi bi-person-circle"></i>
          <span>View Employee</span>
        </a>
      </li>
      <hr/>
      <?php } ?>
      
      <li class="nav-heading">Accounts</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="employee/user-profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="logout.php">
          <i class="bi bi-box-arrow-in-left"></i>
          <span>Sign Out</span>
        </a>
      </li><!-- End Login Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->
