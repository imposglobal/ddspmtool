
<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');

?>
<style>
    .time_frame
    {
        font-size:20px;
        font-weight:500;
        color: #012970 !important;
        font-family: "Poppins", sans-serif;
    }
</style>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Projects</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">Employee Task Analytics</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">   
        <div class="col-lg-12">
          <div class="card">
            <div class="row">   
                <div class="col-lg-12">
                    <div class="card-body">               
                <table class="table">
                <thead>
                  <tr>
                    <th scope="col">S No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Task Name</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">Description</th>                                         
                    <th scope="col">Break Time</th>
                    <th scope="col">Total Time</th>                    
                  </tr>
                </thead>               
                <tbody>
    <?php
    if (isset($_GET['eid']) && isset($_GET['pid'])) {
        $eid = $_GET['eid'];
        $pid = $_GET['pid'];
        ?>
        <div class="col-lg-12 mt-4 mb-4 text-end">
        <form method="POST" action="../../API/export_employee.php">
        <input type="hidden" name="eid" value="<?php echo $eid; ?>">
        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
        <button type="submit" class="btn btn-info">Export to CSV</button>  
        </form>
        </div>
        <?php

        // Main query to fetch task details
        $sql = "SELECT task.tid, task.pid, task.created_at, task.start_date, task.title, task.description, employees.fname, employees.eid, task_time.total_time, break.total_break
                FROM task
                INNER JOIN employees ON task.eid = employees.eid 
                INNER JOIN task_time ON task.tid = task_time.tid AND task.eid = task_time.eid 
                INNER JOIN 
                  -- subquery to fetch total break time
      ( SELECT 
            tid, 
            eid, 
            pid, 
            SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS total_break
        FROM 
            time_difference 
        GROUP BY 
            tid, eid, pid
    ) AS break
ON 
    task.tid = break.tid 
    AND task.eid = break.eid 
    AND task.pid = break.pid

    WHERE task.eid = '$eid' AND task.pid = '$pid'";

        $result1 = mysqli_query($db, $sql);

        // Query to fetch total time frame
        $sql1 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(task_time.total_time))) AS all_task_time 
                 FROM task_time
                 WHERE eid = '$eid' AND pid = '$pid'";
        $result2 = mysqli_query($db, $sql1);

        $sql2 = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(time_difference.time))) AS all_task_break FROM time_difference WHERE pid = '$pid' AND eid = '$eid'";
        $result3 = mysqli_query($db, $sql2);

       

        if ($result1 && $result2 && $result3 ) {
            $i = 1;
            if (mysqli_num_rows($result1) > 0) {
                while ($row1 = mysqli_fetch_assoc($result1)) {
                    // Remove HTML tags from the description
                    $removedesc = strip_tags($row1["description"]);
                    // Decode HTML entities back into their respective characters
                    $decode_desc = html_entity_decode($removedesc);

                    // Convert total_time and total_break to seconds
                    $total_time = strtotime($row1['total_time']);
                    $total_break_time = strtotime($row1['total_break']);
                
                // Subtract total_break from total_time
                $actual_time = $total_time - $total_break_time;
                $time = gmdate('H:i:s', $actual_time);                
                    ?>

                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row1['fname']; ?></td>
                        <td><?php echo $row1['title']; ?></td>
                        <td><?php echo $row1['start_date']; ?></td>
                        <td><?php echo $decode_desc; ?></td>                       
                        <!-- <td><?php echo $row1['total_time']; ?></td>                                                                   -->
                        <td><?php echo substr( $row1["total_break"], 0, 8); ?></td>
                        <td><?php echo $time;?></td>
                        
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='8'>No results found.</td></tr>";
            }
            // Display total time frame
            $row2 = mysqli_fetch_assoc($result2);
            $row3 = mysqli_fetch_assoc($result3);

            list($total_hours, $total_minutes, $total_seconds) = explode(':', $row2['all_task_time']);
            // for total break time
            list($break_hours, $break_minutes, $break_seconds) = explode(':', $row3['all_task_break']);
            // convert hrs and minuts in seconds for total_time 
            // 1 minute = 1 * 60 
            // 1 hr = 60 * 60
            $total_time_seconds = $total_hours * 3600 + $total_minutes * 60 + $total_seconds;

            // convert hrs and minuts in seconds for total_break_time
            // 1 minute = 1 * 60 
            // 1 hr = 60 * 60
            $total_break_time_seconds = $break_hours * 3600 + $break_minutes * 60 + $break_seconds;

            $total_timeframe = $total_time_seconds - $total_break_time_seconds;
           
            // convert total timeframe back to HH:MM:SS format
            $hours = floor($total_timeframe / 3600);
            $minutes = floor(($total_timeframe % 3600) / 60);
            $seconds = $total_timeframe % 60;

           $actual_task_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            ?>
            <tr>
              
                <td  colspan= "6" class="text-end time_frame">Total Time:</td> 
                <!-- <td class="time_frame">Total Task Time:<?php echo  $row2['all_task_time'];?></td> 
                <td class="time_frame">Total Break Time:  <?php echo  $row3['all_task_break'];?></td>                 -->
                <td  colspan= "6" class="time_frame"><?php echo  $actual_task_time; ?></td> 
            </tr>
            <?php
        } 
        else 
        {
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
