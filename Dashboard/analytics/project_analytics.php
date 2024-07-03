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
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/Dashboard/index.php">Home</a></li>
                <li class="breadcrumb-item active">Project Type</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">   
            <div class="col-lg-12 mt-4">
                <div class="card">
                  <div class="row"> 
                        <div class="col-lg-12">
                            <div class="card-body">               
                                <table class="table">
                                    <thead>
                                    <tr>
                                            <th scope="col">S No.</th>
                                            <th scope="col">Start Date</th>
                                            <th scope="col">Project Name</th>                                          
                                            <th scope="col">Project Type</th>
                                            <th scope="col">Task Count</th>
                                            <th scope="col">Employee Name</th>
                                            <th scope="col">Total Time</th>                                          
                                            <th scope="col">Break Time</th> 
                                            <th scope="col">Productive Time</th>                                     
                                    </tr>
                                    </thead>               
                                    <tbody>
    <?php
    if (isset($_GET['project_id'])) {
        $project_id = $_GET['project_id'];

        // Query to fetch project details including tasks, employees, project name, and total project time
        $sql1 = "SELECT task.pid, task.created_at, task.start_date, task.title, task.status, task.description, task.project_type, COUNT(task.tid) AS task_count, projects.project_name, GROUP_CONCAT(DISTINCT CONCAT('<a href=\"employee_task.php?eid=', employees.eid, '&pid=', task.pid, '\">', employees.fname, '</a>') ORDER BY employees.fname SEPARATOR ', ') AS employee_links, SEC_TO_TIME(SUM(TIME_TO_SEC(task_time.total_time))) AS total_project_time FROM task  
        INNER JOIN  projects ON task.pid = projects.pid 
        INNER JOIN  employees ON task.eid = employees.eid
        INNER JOIN task_time ON task.tid = task_time.tid AND task.pid = task_time.pid AND task.eid = task_time.eid
        WHERE task.pid = '$project_id' GROUP BY task.pid, task.project_type";

        $sql2= "SELECT task.pid, task.project_type, SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS total_project_break FROM task 
        INNER JOIN time_difference ON task.tid = time_difference.tid AND task.pid = time_difference.pid AND task.eid = time_difference.eid WHERE task.pid = '$project_id' GROUP BY task.pid, task.project_type"; 

        $result1 = mysqli_query($db, $sql1);
        $result2 = mysqli_query($db, $sql2);

        if (mysqli_num_rows($result1) > 0 && mysqli_num_rows($result2) > 0) {
            $rows1 = [];
            $rows2 = [];

            while ($row = mysqli_fetch_assoc($result1)) {
                $rows1[$row['pid'] . '_' . $row['project_type']] = $row;
            }

            while ($row = mysqli_fetch_assoc($result2)) {
                $rows2[$row['pid'] . '_' . $row['project_type']] = $row;
            }

            $i = 1;
            foreach ($rows1 as $key => $row1) {
                $row2 = isset($rows2[$key]) ? $rows2[$key] : ['total_project_break' => '00:00:00'];
                
                // calculation for total task time
                list($total_hours, $total_minutes, $total_seconds) = explode(':', $row1['total_project_time']);

                // calculation for total break time
                list($break_hours, $break_minutes, $break_seconds) = explode(':', $row2['total_project_break']);

                // convert hrs and minutes in seconds for total task time 
                $total_time_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;

                // convert hrs and minutes in seconds for total break time
                $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;

                // subtract total_break_time_seconds from total_time_seconds
                $total_timeframe = $total_time_seconds - $total_break_time_seconds;

                // convert total timeframe back to HH:MM:SS format
                $hours = floor($total_timeframe / 3600);
                $minutes = floor(($total_timeframe % 3600) / 60);
                $seconds = $total_timeframe % 60;

                $productive_task_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    ?>
             
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row1['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row1['project_name']); ?></td>                   
                    <td class="text-blue"><?php echo htmlspecialchars($row1['project_type']); ?></td>
                    <td><?php echo htmlspecialchars($row1["task_count"]); ?></td>
                    <td><?php echo $row1["employee_links"]; ?></td>
                    <td><?php echo substr($row1["total_project_time"], 0, 8);?></td>
                    <td><?php echo substr($row2["total_project_break"], 0, 8);?></td> 
                    <td class="text-green"><?php echo $productive_task_time; ?></td>
                </tr>

                
    <?php
            }
        } else {
            // Handle case where no projects found for the given project_id
            echo "<tr><td colspan='8'>No projects found for project ID: " . htmlspecialchars($project_id) . "</td></tr>";
        }
    } else {
        // Handle case where project_id is not set
        echo "<tr><td colspan='8'>Project ID is not set.</td></tr>";
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
