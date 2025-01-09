<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');

?>
<style>
    .time_frame {
        font-size: 28px;
        font-weight: 500;
        color: #012970 !important;
        font-family: "Poppins", sans-serif;
    }
    .text-green {
        color: green !important;
        font-weight: 700;
    }

    #status > option:nth-child(2) {
        background-color: green;
        color: #fff;
    }
    #status > option:nth-child(3){
        background-color: #dec016;
        color: #fff;
    }
    #status > option:nth-child(4){
        background-color: #eb7e09;
        color: #fff;
    }
    #status > option:nth-child(5){
        background-color: #eb6709;
        color: #fff;
    }
    #status > option:nth-child(6){
        background-color: red;
        color: #fff;
    }
    .exportbtn
    {
      background-color: #012970;
      color:#fff;
    }
    .text-blue
    {
        color: #012970 !important;
        font-weight: 700;
    }
      
</style>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Employee Task</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/client/dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Employee Task Analytics</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">   
            <div class="col-lg-12">
                <div class="card">
                <form method="GET" action="">
                  <div class="row ms-4">

            <!-- project type -->
            <div class="col-lg-3 mt-4 mb-4">  
            <select class="form-select" name="project_type">
            <option selected="">Select Project Type</option>
            <option value="All">All</option>
            <option value="social-media">Social Media</option>
            <option value="branding">Branding</option>
            <option value="packaging">Packaging</option>
            <option value="ux-ui">UX & UI</option>
            <option value="development">Development</option> 
            </select>
            </div>
            <!-- project type -->
                
            <div class="col-lg-3 mt-4 mb-5">  
                <select class="form-select" name="time_status">
                    <option selected>Select Time </option>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="weekly">This Week</option>
                    <option value="monthly">This Month</option> 
                </select>
            </div>
            <!--Time status-->

            <!-- Task status -->
            <div class="col-lg-3 mt-4 mb-5">  
                <select id="status" class="form-select" name="task_status">
                    <option selected="" disabled="true">Select Status</option>
                    <option value="Completed">Completed</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Pending">Pending</option>
                    <option value="On Hold">On Hold</option>
                    <option value="Abonded">Abonded</option>
                </select>
            </div>
            <!-- Task status -->
            <div class="col-lg-3 mt-4 mb-5"> 
              <?php
              if (isset($_GET['eid']) && isset($_GET['pid'])) {
                $eid = $_GET['eid'];
                $pid = $_GET['pid'];
              }
               ?>  
                <input type="hidden" name="eid" value="<?php echo $eid; ?>">
                <input type="hidden" name="pid" value="<?php echo $pid; ?>"> 
                <button type="submit" name="show" class="btn btn-success">Show</button>   
                <button type="submit" formaction="../../API/export_employee.php" class="btn exportbtn ms-2">Export</button>        
            </div>
        
                  </div>
                  </form>
                    <div class="row"> 
                        <div class="col-lg-12">
                            <div class="card-body">               
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">S No.</th>
                                            <th scope="col">Start Date</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Project_type</th>                                           
                                            <th scope="col">Task Name</th>                                          
                                            <th scope="col">Description</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Total Time</th>                                          
                                            <th scope="col">Break Time</th> 
                                            <th scope="col">Productive Time</th>                                     
                                        </tr>
                                    </thead>               
                                    <tbody>
<?php
if (isset($_GET['eid']) && isset($_GET['pid'])) {
    $eid = $_GET['eid'];
    $pid = $_GET['pid'];
    $project_type = isset($_GET['project_type']) ? $_GET['project_type'] : 'All';
    $time_status = isset($_GET['time_status']) ? $_GET['time_status'] : '';
    $task_status = isset($_GET['task_status']) ? $_GET['task_status'] : '';

     // Initialize where conditions array
     $where_conditions = ["task.eid = '$eid'", "task.pid = '$pid'"];


     // Append filters to the query if a specific project_type is selected
     if ($project_type !== 'All') {
        $where_conditions[] = "task.project_type = '$project_type'";
      }

     // Add conditions based on the selected status
     switch ($time_status) {
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
     if (!empty($task_status)) {
         $where_conditions[] = "task.status = '$task_status'";
     }
 
     // Combine all conditions into a single string
     $where_sql = implode(' AND ', $where_conditions);


        // Main query to fetch task details
        $sql = "SELECT task.tid, task.pid, task.created_at, task.start_date, task.title, task.project_type, task.status, task.description, employees.fname, employees.eid, task_time.total_time, break.all_breaks_of_a_task
                FROM task
                INNER JOIN employees ON task.eid = employees.eid 
                INNER JOIN task_time ON task.tid = task_time.tid AND task.eid = task_time.eid 
                INNER JOIN 
                (SELECT tid, eid, pid, SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS all_breaks_of_a_task
                 FROM time_difference 
                 GROUP BY tid, eid, pid) AS break
                ON task.tid = break.tid AND task.eid = break.eid AND task.pid = break.pid
                WHERE $where_sql ORDER BY  task.created_at DESC";     

      $result = mysqli_query($db, $sql);
        // Query to calculate the sum of all task times to determine the overall task time for all tasks.        
        $sql1 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(task_time.total_time))) AS all_task_time 
                 FROM task_time WHERE eid = '$eid' AND pid = '$pid'";
        $result1 = mysqli_query($db, $sql1);

        // Query to calculate the sum of all task break times to determine the overall task break time for all tasks.
        $sql2 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS all_task_break 
                 FROM time_difference WHERE pid = '$pid' AND eid = '$eid'";
        $result2 = mysqli_query($db, $sql2);

        if ($result && $result1 && $result2) {
            $i = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

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

                     
                    // Remove HTML tags from the description
                    $removedesc = strip_tags($row["description"]);
                    // Decode HTML entities back into their respective characters
                    $decode_desc = html_entity_decode($removedesc);           
                   
                    // calculation for single task
                    // $single_task_total_time = strtotime($row['total_time']);
                    // $single_task_break_time = strtotime($row['all_breaks_of_a_task']);

                    $single_task_total_time = !empty($row['total_time']) ? strtotime($row['total_time']) : 0;
                    $single_task_break_time = !empty($row['all_breaks_of_a_task']) ? strtotime($row['all_breaks_of_a_task']) : 0;

                    // Subtract total_break from total_time
                    // $actual_time = $single_task_total_time - $single_task_break_time;
                    // $single_task_actual_time = gmdate('H:i:s', $actual_time);


                    if ($single_task_total_time > 0) 
                    {
                      $actual_time = $single_task_total_time - $single_task_break_time;
                      $single_task_actual_time = gmdate('H:i:s', $actual_time);
                    } 
                    else 
                    {
                      $single_task_actual_time = ''; 
                    }
?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row['start_date']; ?></td> 
                        <td><?php echo $row['fname']; ?></td>
                        <td class="text-blue"><?php echo $row['project_type']; ?></td>                      
                        <td><?php echo $row['title']; ?></td>                                                                
                        <td><?php echo $decode_desc; ?></td>  
                        <td><?php echo $status;?></td>                                                   
                        <td><?php echo $row['total_time']; ?></td>                                       
                        <td><?php echo substr($row["all_breaks_of_a_task"], 0, 8); ?></td>
                        <td class="text-green"><?php echo $single_task_actual_time; ?></td>
                    </tr>
                <?php
                }
            } else {
                echo "<tr><td>No results found.</td></tr>";
            }
            // Display total time frame
            $row1 = mysqli_fetch_assoc($result1);
            $row2 = mysqli_fetch_assoc($result2);

              // calculation for all task 

            list($total_hours, $total_minutes, $total_seconds) = explode(':', $row1['all_task_time']);
            // for total break time
            list($break_hours, $break_minutes, $break_seconds) = explode(':', $row2['all_task_break']);
            // convert hrs and minutes in seconds for total_time 
            $total_time_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;

            // convert hrs and minutes in seconds for total_break_time
            $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;

            $total_timeframe_of_all_task = $total_time_seconds - $total_break_time_seconds;

            // convert total timeframe back to HH:MM:SS format
            $hours = floor($total_timeframe_of_all_task / 3600);
            $minutes = floor(($total_timeframe_of_all_task % 3600) / 60);
            $seconds = $total_timeframe_of_all_task % 60;

            $actual_time_of_all_task = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
?>
            <tr>
                <td colspan="6" class="text-end time_frame">Total Time:</td> 
                <td colspan="6" class="time_frame"><?php echo $actual_time_of_all_task; ?></td> 
            </tr>
<?php
        } else {
            echo "<tr><td colspan='5'>Error fetching data.</td></tr>";
        }
    
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      
        </div>   
    </section>
</main><!-- End #main -->

<?php 
require('../footer.php');
?>
