<?php
require("db.php");

function get_projects($db, $page = 1, $recordsPerPage = 10){
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;
    
    // Fetch projects with pagination
    $sql = "SELECT * FROM projects LIMIT $offset, $recordsPerPage";
    $result = mysqli_query($db, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
            if($row['status'] == ""){
                $status = "Not Started";
            } else {
                $status = $row['status'];
            }
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>'. $row["project_name"].'</td>';
            echo '<td>'. $row["created_at"].'</td>';
            echo '<td>'. $status.'</td>';
            // echo '<td><a href="project.php?pid='. $row["pid"].'"><i class="bi bi-info-circle-fill"></i> View</td>';
            echo '<td><a href="project.php?pid='. $row["pid"].'"><i class="icon bi bi-info-circle-fill "></i></a> <i class="icon bi bi-pencil-square"></i> <i class="icon text-danger bi bi-trash3"></i></td>';

            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
    
}

//for tasks
function get_tasks($role, $eid, $db, $page = 1, $recordsPerPage = 10){
    // Calculate offset
    $offset = ($page - 1) * $recordsPerPage;
    
    // Fetch projects with pagination
    if($role == 0){
    $sql = "SELECT * FROM task LIMIT $offset, $recordsPerPage";
    }else{
        $sql = "SELECT * FROM task WHERE eid = '$eid' LIMIT $offset, $recordsPerPage";
    }
    $result = mysqli_query($db, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        $i = ($page - 1) * $recordsPerPage + 1;
        while($row = mysqli_fetch_assoc($result)) {
            if($row["m_status"] == ""){
                $mstatus = "Reviewing";
            }else{
                $mstatus = $row["m_status"];
            }
            if($row["status"] = "Completed"){
                $status = '<td> <span style="background:green; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
            }elseif($row["status"] = "In Progress"){
                $status = '<td> <span style="background:#dec016; color:#fff; padding:2px 8px;">'. $row["status"].' </span></td>';
            }
            echo '<tr>';
            echo '<th scope="row">'. $i++.'</th>';
            echo '<td>'. $row["title"].'</td>';
            echo '<td>'. $row["created_at"].'</td>';
            echo '<td>'. round($row["timeframe"],2).' Hrs</td>';
            echo $status;
            echo '<td>'. $mstatus.'</td>';
            echo '<td><a href="project.php?tid='. $row["tid"].'"><i class="bi bi-info-circle-fill"></i> View</td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No results found.</td></tr>";
    }
    
}

function pagination($currentPage, $totalPages){
    echo '<tr><td colspan="5">';
    echo '<ul class="pagination justify-content-center">';
    
    // Previous page link
    if ($currentPage > 1) {
        echo '<li class="page-item"><a class="page-link" href="?page='.($currentPage - 1).'">Previous</a></li>';
    }

    // Page links
    for ($i = 1; $i <= $totalPages; $i++) {
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

//get task count
function get_task_count($role, $eid, $db)
{
  if($role==0)
  {
    $sql = "SELECT COUNT(*) FROM task";
    $result = mysqli_query($db, $sql);
  }
  else{
    $sql = "SELECT COUNT(*) FROM task WHERE eid = '$eid'";
    $result = mysqli_query($db, $sql);
  }

  // Assuming you want to return the count value
  $count = mysqli_fetch_array($result)[0];
  return $count;
  
}

// get projects count

function get_project_count($role, $eid, $db)
{
    if($role==0)
    {
      $sql = "SELECT COUNT(DISTINCT projects.pid) AS pid_count FROM projects INNER JOIN task ON projects.pid = task.pid";
      $result = mysqli_query($db, $sql);
    }
    else{
      $sql = "SELECT COUNT(DISTINCT projects.pid) AS pid_count FROM projects INNER JOIN task ON projects.pid = task.pid 
      WHERE task.eid = '$eid';";  
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

// get projects by current date
function get_projects_by_current_date($role, $eid, $db)
{
    $date = date("y-m-d");
    //echo $date;
    if ($role == 0) {
        $sql = "SELECT * FROM task WHERE DATE(created_at) = '$date'";
    } else {
        $sql = "SELECT * FROM task WHERE DATE(created_at) = '$date'";
    }
     $i =1;
    $result = mysqli_query($db, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<th scope="row">'.$i++.'</th>';
            echo '<td>' . $row["task_type"] . '</td>';
            echo '<td>' . $row["title"] . '</td>';
            echo '<td>' . $row["timeframe"] . '</td>';
            echo '<td>' . $row["m_status"] . '</td>';
            echo '<td>' . $row["created_at"] . '</td>';
            echo '</tr>';
        }
    } 
}

?>
