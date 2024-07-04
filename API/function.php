<style>
    .pagination-box{
        margin-left:376px;
    }
    .text-green {
        color: green !important;
        font-weight: 700;
    }
</style>

<?php
require("db.php");

function get_projects($db, $page = 1, $recordsPerPage = 10){
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;
    
    // Fetch projects with pagination
    $sql = "SELECT * FROM projects ORDER BY created_at DESC LIMIT $offset, $recordsPerPage";
    $result = mysqli_query($db, $sql);
    
    if (mysqli_num_rows($result) > 0) 
    {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
            // if($row['status'] == ""){
            //     $status = "Not Started";
            // } else {
            //     $status = $row['status'];
            // }

            if($row["status"] == "") {
                $status = '<span style="background:#fff; color:#000; padding:2px 8px;">Not Started</span>';
            } elseif($row["status"] == "Completed") {
                $status = '<span style="background:green; color:#fff; padding:2px 8px;">'. $row["status"].' </span>';
            } elseif($row["status"] == "In Progress") {
                $status = '<span style="background:#dec016; color:#fff; padding:2px 8px;">'. $row["status"].' </span>';
            } elseif($row["status"] == "Pending") {
                $status = '<span style="background:#eb7e09; color:#fff; padding:2px 8px;">'. $row["status"].' </span>';
            } elseif($row["status"] == "On Hold") {
                $status = '<span style="background:#eb6709; color:#fff; padding:2px 8px;">'. $row["status"].' </span>';
            } elseif($row["status"] == "Abandoned") {
                $status = '<span style="background:red; color:#fff; padding:2px 8px;">'. $row["status"].' </span>';
            }
              
            
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>'. $row["project_name"].'</td>';
            echo '<td>'. $row["created_at"].'</td>';
            echo '<td>'. $status.'</td>';
            echo '<td>
            <a href="../../Dashboard/project/view_project_detail.php?pid='. $row["pid"].'"><i class="icon bi bi-info-circle-fill "></i></a>  <i  onclick="deleteProject('. $row["pid"].',\''. $row["project_name"].'\')" class="icon text-danger bi bi-trash3"></i></td>';

            echo '</tr>';
        }
    } 
    else 
    {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
    
}


// for timer tasks
function get_tasks($role, $eid, $db, $page = 1, $recordsPerPage = 10)
{
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;
    // query to retrive data from task table
    if($role == 0)
    {
        // $sql = "SELECT * FROM task INNER JOIN employees ON task.eid = employees.eid ORDER BY task.created_at DESC LIMIT $offset, $recordsPerPage";

        $sql = "SELECT task.tid, task.start_date, task.end_date, task.task_type, task.eid, task.pid, task.title, task.description, task.status, task.estimated_time, task.priority, task.m_status, task.feedback, DATE(task.created_at) as created_date, employees.fname, employees.lname, employees.eid  
        FROM 
            task 
        INNER JOIN 
            employees 
        ON 
            task.eid = employees.eid 
        ORDER BY 
            task.created_at DESC 
        LIMIT 
            $offset, $recordsPerPage";
    } else 
    {
        // $sql = "SELECT * FROM task INNER JOIN employees ON task.eid = employees.eid WHERE employees.eid = '$eid' 
        // ORDER BY task.created_at DESC LIMIT $offset, $recordsPerPage";
        $sql = "SELECT task.tid, task.start_date, task.end_date, task.task_type, task.eid, task.pid, task.title, task.description, task.status, task.estimated_time, task.priority, task.m_status, task.feedback, DATE(task.created_at) as created_date , employees.fname, employees.lname, employees.eid  
        FROM 
            task 
        INNER JOIN 
            employees 
        ON 
            task.eid = employees.eid 
        WHERE 
            employees.eid = '$eid' 
        ORDER BY 
            task.created_at DESC 
        LIMIT 
            $offset, $recordsPerPage";
    }
    $result = mysqli_query($db, $sql);  
    if (mysqli_num_rows($result) > 0) {
       
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
            $tid = $row["tid"];
            $eid = $row["eid"];
            $pid = $row["pid"];
            $title = $row["title"];
            if($row["m_status"] == ""){
                $mstatus = "Reviewing";
            } else {
                $mstatus = $row["m_status"];
            }
            if($row["status"] == "Completed"){
                $status = '<td> <span style="background:green; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
            } elseif($row["status"] == "In Progress"){
                $status = '<td> <span style="background:#dec016; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
            } elseif($row["status"] == "Pending"){
                $status = '<td> <span style="background:#eb7e09; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
            } elseif($row["status"] == "On Hold"){
                $status = '<td> <span style="background:#eb6709; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
            } elseif($row["status"] == "Abandoned"){
                $status = '<td> <span style="background:red; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
            }
           
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>' . htmlspecialchars($row["created_date"]) . '</td>';
            echo '<td>'. $row["fname"].'</td>';
            // code to retrieve title from task table where it will show only 20 character title
            if (strlen($title) > 80)
            {
               echo '<td>'. substr($title , 0, 70) . '...' .'</td>';
            }
            else
            {
                echo '<td>'. $row["title"].'</td>';
            }                     
            echo $status;
            echo '<td>'. $mstatus.'</td>';

            //Code to retrieve the complete status after the task has been completed.

            $query = "SELECT `end_time` FROM `task_time` WHERE `tid` = '$tid'";
            $etime = $db->query($query);
            $row = $etime->fetch_assoc();
            $end_time = null; // Initialize $end_time
            if ($row && isset($row['end_time']))
            {
               $end_time = $row['end_time'];
            }
                if(!empty($end_time)) 
                {
                    // Task is completed
                    echo '<td><div><h6>Completed</h6></div></td>';
                } 
                else
                {
                    // Task is started
                echo '<td>
                <div class="jumbotron">
                <div class="time-buttons">
                <form id="timeForm">';
                // Add unique id for each button
                echo '<input type="hidden" id="tid" value="' . $tid . '">';
                echo '<input type="hidden" id="eid" value="' . $eid . '">';
                echo '<input type="hidden" id="pid" value="' . $pid . '">';
                echo '<button type="button" onclick="startTimer(' . $tid . ')" name="start_time" id="start_time_' . $tid . '"  style="border:none;background-color:transparent"><i class="fas fa-play" style="color:green;"></i></button>';   
                echo '<button type="button" onclick="pauseTimer(' . $tid . ')" name="pause_time" id="pause_time_' . $tid . '" style="margin-left:10px;border:none;background-color:transparent;">
                        <i class="fas fa-pause" style="color:orange;"></i> 
                      </button>'; 
                      
                //   After clicking on the pause button, it will show the options to select the break reason.

                echo '
                <div id="time_select_' . $tid . '" class="alert-box" style="display:none;">
                    <select id="select_reason_' . $tid . '">
                        <option selected>Add Reason</option>
                        <option value="Meeting">Meeting</option>
                        <option value="Call">Call</option>
                        <option value="Bio-Break">Bio Break</option>
                        <option value="Discussion">Discussion</option>
                        <option value="Issues">Issues</option>
                        <option value="Lunch/Tea Break">Lunch/tea Break</option>
                        <option value="Other-Task">Other Task</option>
                    </select>
                    <input type="hidden" id="tid" value="' . $tid . '">
                    <input type="hidden" id="eid" value="' . $eid . '">
                    <input type="hidden" id="pid" value="' . $pid . '">
                    <button type="button" class="submit_time">Submit</button>
                </div>
                ';
                echo '<button type="button" onclick="stopTimer(' . $tid . ')" name="stop_time" id="stop_time_' . $tid . '" disabled style="margin-left:10px;border:none;background-color:transparent;"><i class="fas fa-stop" style="color:red;"></i></button>';   
                echo '<p id="timerDisplay'.$tid .'" class="taskmessage"></p>';         
                echo '</form>
                        </div>
                    </div>
                </td>';  
                }
            echo '<td><a href="../../Dashboard/task/view_task_detail.php?tid='. $tid.'&eid='. $eid.'"><i class="bi bi-info-circle-fill"></i>  <a class="ms-2" onclick="deleteTask('.$tid.')"><i class="icon text-danger bi bi-trash3"></i></a> </td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
}




// pagination new code

function pagination($currentPage, $totalPages)
{
    echo '<tr><td colspan="5">';
    echo '<ul class="pagination justify-content-center">';
    
    // Previous page link
    if ($currentPage > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page='.($currentPage - 1).'">Previous</a></li>';
    }

    // Determine the range of page links to show
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);

    if ($currentPage <= 3) {
        $startPage = 1;
        $endPage = min(5, $totalPages);
    } elseif ($currentPage >= $totalPages - 2) {
        $endPage = $totalPages;
        $startPage = max(1, $totalPages - 4);
    }

    // Page links
    for ($i = $startPage; $i <= $endPage; $i++) {
        echo '<li class="page-item ';
        if ($i == $currentPage) echo 'active';
        echo '"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
    }

    // Next page link
    if ($currentPage < $totalPages) {
        echo '<li class="page-item"><a class="page-link" href="?page='.($currentPage + 1).'">Next</a></li>';
    }
    
    echo '</ul>';
    echo '</td></tr>';
}



//get task count in dashboard
function get_task_count($role, $eid, $db)
{
  if($role==0)
  {
    $sql = "SELECT COUNT(*) FROM task WHERE MONTH(created_at) = MONTH(CURRENT_DATE())";
    $result = mysqli_query($db, $sql);
  }
  else{
    $sql = "SELECT COUNT(*) FROM task WHERE eid = '$eid' AND MONTH(created_at) = MONTH(CURRENT_DATE())";
    $result = mysqli_query($db, $sql);
  }

  // Assuming you want to return the count value
  $count = mysqli_fetch_array($result)[0];
  return $count;
  
}

// get projects count in dashboard

function get_project_count($role, $eid, $db)
{
    if($role==0)
    {
      $sql = "SELECT COUNT(DISTINCT pid) FROM task WHERE MONTH(created_at) = MONTH(CURRENT_DATE());";
      $result = mysqli_query($db, $sql);
    }
    else{
    //   $sql = "SELECT COUNT(DISTINCT projects.pid) AS pid_count FROM projects INNER JOIN task ON projects.pid = task.pid 
    //   WHERE task.eid = '$eid' AND projects.status = 'In Progress';";  
    $sql = "SELECT COUNT(DISTINCT pid) FROM task WHERE eid = '$eid' AND MONTH(created_at) = MONTH(CURRENT_DATE());";
      $result = mysqli_query($db, $sql);
    }
  
    // Assuming you want to return the count value
    $count = mysqli_fetch_array($result)[0];
    return $count;
}

//for check present or not
function getprensentStatus($db,$eid){
    $date = date('Y-m-d');
    $sql = "SELECT * FROM attendance WHERE login_time IS NOT NULL AND date = '$date' AND eid = '$eid'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "present";
    }else{
        echo "absent";
    }
}

// get projects by current date in dashboard
function get_projects_by_current_date($role, $eid, $db)
{
    $date = date("y-m-d");
    //echo $date;
    if ($role == 0) {
        $sql = "SELECT task.eid, task.task_type, task.title, task.estimated_time, task.m_status, DATE(task.created_at) as created_date, employees.fname, employees.lname, employees.eid 
        FROM task 
        INNER JOIN employees ON task.eid = employees.eid 
        WHERE DATE(task.created_at) = '$date' ORDER BY task.created_at DESC";
    } else {
        $sql = "SELECT task.eid, task.task_type, task.title, task.estimated_time, task.m_status, DATE(task.created_at) as created_date, employees.fname, employees.lname, employees.eid 
        FROM task 
        INNER JOIN employees ON task.eid = employees.eid 
        WHERE DATE(task.created_at) = '$date' AND task.eid = '$eid' ORDER BY task.created_at DESC";
    }
     $i =1;
    $result = mysqli_query($db, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row["title"];
            echo '<tr>';
            echo '<th scope="row">'.$i++.'</th>';
            echo '<td>' . htmlspecialchars($row["created_date"]) . '</td>';
            echo '<td>' . $row["fname"] . '</td>';
            echo '<td>' . $row["task_type"] . '</td>';

            // code to retrieve title from task table where it will show only 20 character title
            if (strlen($title) > 70)
            {
              echo '<td>'. substr($title , 0, 70) . '...' .'</td>';
            }
            else
            {
              echo '<td>'. $row["title"].'</td>';
            }   
            echo '<td>' . $row["estimated_time"] . '</td>';
            echo '<td>' . $row["m_status"] . '</td>';
            
            echo '</tr>';
        }
    } 
}


// get Attendance count in dashboard

function get_attendance_count($role, $eid, $db)
{
    if($role==0)
    {
      $sql = "SELECT COUNT(DISTINCT date) FROM attendance";  
      $result = mysqli_query($db, $sql);
    }
    else{      
      $sql = "SELECT COUNT(DISTINCT date) FROM attendance WHERE eid = '$eid'";
      $result = mysqli_query($db, $sql);
    }
  
    // Assuming you want to return the count value
    $count = mysqli_fetch_array($result)[0];
    return $count;
}


// fetch assigned projects

function get_assigned_project($db, $pid)
{
   // Query to retrieve project details including associated employee
   $sql = "SELECT employees.fname 
   FROM projects 
   INNER JOIN project_assign AS pa ON projects.pid = pa.pid 
   INNER JOIN employees ON pa.eid = employees.eid 
   WHERE projects.pid = '$pid'";

   $result = mysqli_query($db, $sql);

    // Check if the query was successful and if there are results
    if ($result && mysqli_num_rows($result) > 0) {
        if ($result)
    {
        while ($row = mysqli_fetch_assoc($result))
        {
            echo $row["fname"] . ", "; // Concatenate fname with a space
        }     
    }
    } 
    else {
        return false; // Return false if no results or error
    }
}




//  to get task analytics 


function get_task_analytics($db, $page = 1, $recordsPerPage = 10)
{
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;

    $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : '';
    

    // Initialize where conditions array
    $where_conditions = [];

    // Append filters to the query if a specific project is selected
    if ($project_id !== 'All' && $project_id !== '') 
    {
     $where_conditions[] = "task.pid = '$project_id'";
    }
    
    // In the below SQL query, I have joined the task table with the projects table and the employee table. I used the GROUP BY clause to count the total number of tasks and the total number of employees based on the pid (project ID)

    // GROUP_CONCAT is an aggregate function in MySQL that concatenates values from multiple rows into a single string 
    // <a href ="employee_task.php?eid = employees.eid & pid= task.pid">employees.fname</a>

    $sql = "SELECT projects.project_name, projects.status, projects.start_date, projects.end_date, DATE(projects.created_at) AS created_date, task.title, task.pid, task.description, 
    employees.eid, GROUP_CONCAT(DISTINCT CONCAT('<a href=\"employee_task.php?eid=', employees.eid, '&pid=', task.pid, '\">', employees.fname, '</a>') ORDER BY employees.fname SEPARATOR ', ') AS employee_links, COUNT(task.tid) AS total_tasks, COUNT(DISTINCT task.eid) AS total_employees FROM task 
    INNER JOIN projects ON task.pid = projects.pid  
    INNER JOIN employees ON task.eid = employees.eid";

    // Combine where conditions if any
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }

    // Add group by, order by, and limit clauses
       $sql .= " GROUP BY projects.pid ORDER BY created_date DESC LIMIT $offset, $recordsPerPage";
  
    $result = mysqli_query($db, $sql);
    
    if (mysqli_num_rows($result) > 0) 
    {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) 
        {
            $pid =  $row["pid"]; 
            
            // query to fetch sum of total time of any projects
            $sql1 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(task_time.total_time))) AS total_project_time FROM task_time
            WHERE pid = '$pid'";
            $result1 = mysqli_query($db, $sql1);
            $row1 = mysqli_fetch_assoc($result1);

            // query to fetch total break time of any project
            $sql2 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS total_project_break FROM time_difference WHERE pid = '$pid'";
            $result2 = mysqli_query($db, $sql2);
            $row2 = mysqli_fetch_assoc($result2);

            // for total task time
            list($total_hours, $total_minutes, $total_seconds) = explode(':', $row1['total_project_time']);

            // for total break time
            list($break_hours, $break_minutes, $break_seconds) = explode(':', $row2['total_project_break']);

            // convert hrs and minuts in seconds for total task time 
            // 1 minute = 1 * 60 = 60
            // 1 hr = 60 * 60 = 3600
            $total_time_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;

             // convert hrs and minuts in seconds for total break time
            // 1 minute = 1 * 60 = 60
            // 1 hr = 60 * 60 = 3600
            $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;

            // To get the actual time frame, subtract total_break_time_seconds from total_time_seconds

            $total_timeframe = $total_time_seconds - $total_break_time_seconds;
           
            // convert total timeframe back to HH:MM:SS format
            $hours = floor($total_timeframe / 3600);
            $minutes = floor(($total_timeframe % 3600) / 60);
            $seconds = $total_timeframe % 60;

           $actual_task_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td><a href="project_analytics.php?project_id=' . $row["pid"] . '">' . htmlspecialchars($row["project_name"]) . '</a></td>';
            echo '<td>'. $row["total_tasks"].'</td>';                 
            echo '<td>'. $row["total_employees"].'</td>';          
            echo '<td>'. $row["employee_links"].'</td>';                    
            echo '<td>' .substr($row1["total_project_time"], 0, 8).' </td>';
            echo '<td>' .substr($row2["total_project_break"], 0, 8) .' </td>'; 
            echo '<td class="text-green">' . $actual_task_time .'</td>';                
            echo '</tr>';
           
        }
    } 
    else 
    {
        echo "<tr><td colspan='4'>No results found.</td></tr>";
    }
}

// In below function code for pagination is added by krushna
// to get tasks by filter 

function get_tasks_by_filter($role, $eid, $db, $page = 1, $recordsPerPage = 10)
{
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $project_id = isset($_GET['project_id']) ? $_GET['project_id'] : '';
        $project_type = isset($_GET['project_type']) ? $_GET['project_type'] : '';
        $employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : '';
        $task_status = isset($_GET['task_status']) ? $_GET['task_status'] : '';
        $time_status = isset($_GET['time_status']) ? $_GET['time_status'] : '';
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

        // Calculate offset
        $offset = ($page - 1) * $recordsPerPage;
        

        // Query to retrieve data from task table
        $sql = "SELECT task.tid, task.start_date, task.end_date, task.task_type, task.project_type, task.eid, task.pid, task.title, task.description, task.status, task.estimated_time, task.priority, task.m_status, task.feedback, DATE(task.created_at) as created_date, employees.fname, employees.lname, employees.eid  
                FROM task INNER JOIN employees ON task.eid = employees.eid";

        // Initialize where conditions array
        $where_conditions = [];

         // Add role-specific condition
        if ($role != 0) {
            $where_conditions[] = "task.eid = '$eid'";
        }

        // Append filters to the query if a specific project is selected
        if (!empty($project_id) && $project_id !== 'All') {
            $where_conditions[] = "task.pid = '$project_id'";
        }

        if (!empty($project_type) && $project_type !== 'All') {
            $where_conditions[] = "task.project_type = '$project_type'";
        }

        if (!empty($employee_id) && $employee_id !== 'All') {
            $where_conditions[] = "task.eid = '$employee_id'";
        }

        // Add conditions based on the selected status
        switch ($time_status) 
        {
            case 'today':
                $where_conditions[] = "DATE(task.created_at) = CURRENT_DATE()";
                break;
            case 'yesterday':
                $where_conditions[] = "DATE(task.created_at) = CURRENT_DATE() - INTERVAL 1 DAY";
                break;
            case 'weekly':
                $where_conditions[] = "WEEK(task.created_at) = WEEK(CURRENT_DATE())";
                break;
            case 'monthly':
                $where_conditions[] = "MONTH(task.created_at) = MONTH(CURRENT_DATE())";
                break;
        }

        // Add the task status condition if selected
        if (!empty($task_status) && $task_status !== 'Select Task Status') {
            $where_conditions[] = "task.status = '$task_status'";
        }

        // Append filters to the query
        if (!empty($start_date)) {
        $where_conditions[] = "task.created_at >= '$start_date'";
        }
        if (!empty($end_date)) {
        $where_conditions[] = "task.created_at <= '$end_date'";
        }

        // If there are any conditions, they are combined and appended to the SQL query.
        if (!empty($where_conditions)) 
        {
            $sql .= " WHERE " . implode(" AND ", $where_conditions);
        }

        $sql .= " ORDER BY task.created_at DESC LIMIT $offset, $recordsPerPage";

        // Execute the query
        $result = mysqli_query($db, $sql);

        // Check for errors
        if (!$result) {
            die('Error: ' . mysqli_error($db));
        }

        // Initialize table HTML
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th scope="col">#</th>';
        echo '<th scope="col">Date</th>';
        echo '<th scope="col">Employee</th>';
        echo '<th scope="col">Task Title</th>';     
        echo '<th scope="col">Status</th>';
        echo '<th scope="col">M Status</th>';
        echo '<th scope="col">Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if (mysqli_num_rows($result) > 0) 
        {  
            $i = ($page - 1) * $recordsPerPage + 1;
            while($row = mysqli_fetch_assoc($result)) {
                $tid = $row["tid"];
                $eid = $row["eid"];
                $pid = $row["pid"];
                $title = $row["title"];
                if($row["m_status"] == ""){
                    $mstatus = "Reviewing";
                } else {
                    $mstatus = $row["m_status"];
                }
                if($row["status"] == "Completed"){
                    $status = '<td> <span style="background:green; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
                } elseif($row["status"] == "In Progress"){
                    $status = '<td> <span style="background:#dec016; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
                } elseif($row["status"] == "Pending"){
                    $status = '<td> <span style="background:#eb7e09; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
                } elseif($row["status"] == "On Hold"){
                    $status = '<td> <span style="background:#eb6709; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
                } elseif($row["status"] == "Abandoned"){
                    $status = '<td> <span style="background:red; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
                }

                echo '<tr>';
                echo '<th scope="row">'. $i++.'</th>';
                echo '<td>' . htmlspecialchars($row["created_date"]) . '</td>';
                echo '<td>'. $row["fname"].'</td>';
                // code to retrieve title from task table where it will show only 20 character title
                if (strlen($title) > 70)
                {
                    echo '<td>'. substr($title , 0, 70) . '...' .'</td>';
                }
                else
                {
                    echo '<td>'. $row["title"].'</td>';
                }         
                
                echo $status;
                echo '<td>'. $mstatus.'</td>';            
                echo '<td><a href="../../Dashboard/task/view_task_detail.php?tid='. $tid.'&eid='. $eid.'"><i class="bi bi-info-circle-fill"></i>  <a class="ms-2" onclick="deleteTask('.$tid.')"><i class="icon text-danger bi bi-trash3"></i></a> </td>';
                echo '</tr>';
            }
        } 
        else 
        {
            echo "<tr><td colspan='5'>No results found.</td></tr>";
        }

        echo '</tbody>';
        echo '</table>';

        // Calculate the total number of records
        $count_sql = "SELECT COUNT(*) as count FROM task INNER JOIN employees ON task.eid = employees.eid";
        if (!empty($where_conditions)) 
        {
            $count_sql .= " WHERE " . implode(" AND ", $where_conditions);
        }
        $count_result = mysqli_query($db, $count_sql);
        $row = mysqli_fetch_assoc($count_result);
        $totalRecords = $row['count'];
        $totalPages = ceil($totalRecords / $recordsPerPage);

        // Display pagination links
        $range = 4; // Number of pages to display on both side of the current page
        echo '<nav class="mt-5">';
        echo '<ul class="pagination pagination-box">';
        
        // Previous Page Link
        if ($page > 1) {
            echo '<li class="page-item">';
            echo '<a class="page-link" href="?page=' . ($page - 1) . '&project_id=' . $project_id . '&employee_id=' . $employee_id.'&task_status='.$task_status.'&time_status='.$time_status.'">Previous</a>';
            echo '</li>';
        }

        
        // Page Number Links
        for ($i = max(1, $page - $range); $i <= min($totalPages, $page + $range); $i++) {
            echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '">';
            echo '<a class="page-link" href="?page=' . $i . '&project_id=' . $project_id . '&employee_id=' . $employee_id.'&task_status='.$task_status.'&time_status='.$time_status.'">' . $i . '</a>';
            echo '</li>';
        }

        // Next Page Link
        if ($page < $totalPages) {
            echo '<li class="page-item">';
            echo '<a class="page-link" href="?page=' . ($page + 1) . '&project_id=' . $project_id . '&employee_id=' . $employee_id.'&task_status='.$task_status.'&time_status='.$time_status.'">Next</a>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</nav>';
    }    
}


// get In-Progress count in dashboard

function get_in_progress_task_count($role, $eid, $db)
{
    if($role==0)
    {
      $sql = "SELECT COUNT(DISTINCT tid) FROM task WHERE status = 'In Progress' AND MONTH(created_at) = MONTH(CURRENT_DATE())";  
      $result = mysqli_query($db, $sql);
    }
    else{      
      $sql = "SELECT COUNT(DISTINCT tid) FROM task WHERE eid = '$eid' AND status = 'In Progress' AND MONTH(created_at) = MONTH(CURRENT_DATE())";
      $result = mysqli_query($db, $sql);
    }
  
    // Assuming you want to return the count value
    $count = mysqli_fetch_array($result)[0];
    return $count;
}
?>
