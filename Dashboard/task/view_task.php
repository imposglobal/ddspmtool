<?php 
require('../header.php');
?>
<title>My Tasks - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');
?>
<style>
    .ctitle {
    padding: 20px 0 0px 0;
    font-size: 16px;
    font-weight: 400;
    color: #012970;
    font-family: "Poppins", sans-serif;
}
.swal2-title {
        color: #012970;
        font-size: 20px;
        font-weight: 600;
        font-family: "Poppins", sans-serif;
    }


    /* style for alert box */

    .alert-box {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fffefe;
    /* border: 1px solid #ccc; */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    padding: 30px 30px;
    z-index: 9999;
    width:32em;   
    border-radius:10px;
    font-size: 1rem;
}

.alert-box select {
    display: block;
    margin-bottom: 30px;
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.alert-box button {
    display: block;
    width: 100%;
    padding: 8px 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.alert-box button:hover {
    background-color: #0056b3;
}

.taskmessage
{
    color: #012970;
    font-size: 13px;
    font-family: "Open Sans", sans-serif;
    padding-top:3px;
 
}

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Tasks</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo $base_url;?>/Dashboard/index.php">Home</a></li>
          <li class="breadcrumb-item active">Tasks</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="col-lg-12">
          <div class="card">
            <div class="row">   
                <div class="col-lg-12">
                    <div class="card-body">
                        <h5 class="card-title pb-1 pt-4"> Tasks</h5>
                        <hr>
                        <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col"> Employeee</th>
                    <th scope="col">Task Name</th>
                    <th scope="col">Date</th>                 
                    <th scope="col">My Status</th>
                    <th scope="col">Manager Status</th>
                    <th scope="col">Timeframe</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Usage:
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $recordsPerPage = 10;
                    get_tasks($role,$eid,$db, $page, $recordsPerPage);
                  ?>                 
                </tbody>
              </table>
                    </div>
                </div>
            </div>
          </div>

        </div>
        
      </div>

      <!-- pagination -->
      <?php 
      if($role == 0){
        $sql = "SELECT COUNT(*) AS total FROM task";
      }else{
        $sql = "SELECT COUNT(*) AS total FROM task WHERE eid ='$eid'";
      }
      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_assoc($result);
      $totalRecords = $row['total'];
      $totalPages = ceil($totalRecords / $recordsPerPage);
      
      pagination($page, $totalPages);
      ?>
    </section>

  </main><!-- End #main -->

 
<?php 
require('../footer.php');
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<!-- ajax code for start time -->
<script>
$(document).ready(function() {
    // Use event delegation to handle clicks on buttons
    $(document).on('click', '[id^="start_time_"]', function() {
        // Store the button element in a variable 
        var $startButton = $(this);
        var tid = $startButton.siblings('#tid').val(); 
        var eid = $startButton.siblings('#eid').val(); 
        var pid = $startButton.siblings('#pid').val(); 
        $.ajax({
            type: "POST",
            url: "../../API/update.php",
            data: { ops: 'start_task_time', tid: tid , eid: eid , pid: pid},
            success: function(response) 
                {
                // Parse JSON response
                var data = JSON.parse(response);
                if (data.success) {
                    // Show SweetAlert success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Task has started',
                    });
                } else {
                    // Show SweetAlert error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            },
            error: function(xhr, status, error) {
                // Show SweetAlert error message for AJAX error
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX Error',
                    text: 'An error occurred while processing your request. Please try again later.'
                });
            },
        });
    });
});
</script>
<!-- ajax code for start time -->



<!-- ajax  code for pause time -->
<script>
    $(document).ready(function() {
    // When any button with class "pause_time" is clicked
    $('button[name^="pause_time"]').click(function() {
        // Get the ID of the button that was clicked
        var tid = $(this).attr('id').split('_')[2];
        // Show or hide the select box based on its current visibility
        $('#time_select_' + tid).toggle();
    });

    // When any button with class "submit_time" is clicked
    $('button.submit_time').click(function() {
        // var tid = $(this).closest('div').attr('id').split('_')[2];
        var tid = $(this).siblings('input[type="hidden"]').val();
        var eid = $('#eid').val(); 
        var pid = $('#pid').val(); 
        var reason = $('#select_reason_' + tid).val();        
        $.ajax({
            type: "POST",
            url: "../../API/update.php",
            data: { ops: 'pause_task_time', tid: tid , eid: eid , pid: pid, reason: reason },
            success: function(response) {
                // Parse JSON response
                var data = JSON.parse(response); 
                if (data.success) {
                    // Show SweetAlert success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        //  text: data.message
                        text: 'You are on pause'
                    })
                } else {
                    // Show SweetAlert error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            },
            error: function(xhr, status, error) {
                // Show SweetAlert error message for AJAX error
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX Error',
                    text: 'An error occurred while processing your request. Please try again later.'
                });
            },
            complete: function() {
                // Hide the time_select block after the AJAX request is completed
                $('#time_select_' + tid).hide();
            }
        });
    });
});
</script>
<!-- ajax code for pause time -->

<!-- ajax code for stop time -->
<script>
$(document).ready(function() {
      $(document).on('click', '[id^="stop_time_"]', function() {
        var tid = $(this).siblings('#tid').val(); 
        var eid = $(this).siblings('#eid').val(); 
        var pid = $(this).siblings('#pid').val();  
        $.ajax({
            type: "POST",
            url: "../../API/update.php",
            data: { ops: 'stop_task_time', tid: tid , eid: eid , pid: pid},
            success: function(response) {
                // Parse JSON response
                var data = JSON.parse(response); 
                if (data.success) {
                   // Show SweetAlert success message
                   Swal.fire({
                       icon:  'success',
                       title: 'Success',
                       text:  'your task has stopped',
                      
                   }).then(function() {
                        // Reload the page after the alert is closed
                        location.reload();
                    });
               } else {
                   // Show SweetAlert error message
                   Swal.fire({
                       icon: 'error',
                       title: 'Error',
                       text: data.message
                   });
               }

            },
            error: function(xhr, status, error) {
                // Show SweetAlert error message for AJAX error
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX Error',
                    text: 'An error occurred while processing your request. Please try again later.'
                });
            }

        });
    });
});
</script>
<!-- end -->


<!-- delete task -->

<script> 
function deleteTask(tid) {
    Swal.fire({
        title: 'Are you sure to delete this task?',
        text: 'You are about to delete this task. This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, proceed with deletion
            $.ajax({
                url: "../../API/delete.php",
                type: 'POST',
                data: { ops: 'deleteTask', tid: tid}, 
                success: function (response) {
                    if (response === "true") {
                        Swal.fire({
                            icon: 'success',
                            title: 'deleted successfully!',
                            text: ''
                        }).then(function() {
                            // Reload the page after successful deletion
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting user: ' + error
                    });
                }
            });
        }
    });
}
</script>

<!-- code for add timer messages -->

<script>
    var timerInterval;
    var isPaused = false;
    
    function startTimer(tid) 
    {
        var timerDisplay = document.getElementById("timerDisplay" + tid);
        timerDisplay.textContent = "Task started!";
        localStorage.setItem("timerMessage_" + tid, "Task started!");
    }

    function pauseTimer(tid) {
        var timerDisplay = document.getElementById("timerDisplay" + tid);
        clearInterval(timerInterval);
        timerDisplay.textContent = "You are on pause";
        localStorage.setItem("timerMessage_" + tid, "You are on pause");
        isPaused = true;
    }

    function stopTimer(tid) {
        var timerDisplay = document.getElementById("timerDisplay" + tid);
        clearInterval(timerInterval);
        timerDisplay.textContent = "Timer stopped";
        localStorage.removeItem("timerMessage_" + tid);
    }

    function retrieveTimerMessages() {
    // Check if localStorage is supported
    if (typeof localStorage === "undefined") {
        console.error("localStorage is not supported in this browser.");
        return;
    }

    // Loop through localStorage items
    for (var key in localStorage) {
        // Check if the item is a timer message
        if (key.startsWith("timerMessage_")) {
            // Get the status and message from localStorage
            var status = localStorage.getItem(key);
            var tid = key.split("_")[1]; 

            // Find timer display element
            var timerDisplay = document.getElementById("timerDisplay" + tid);

            // Check if timer display element exists
            if (timerDisplay) {
                // Update text content based on status
                timerDisplay.textContent = status;
            } else {
                console.error("Timer display element not found for timer ID: " + tid);
            }
        }
    }
}

// Call retrieveTimerMessages when the page loads
window.onload = retrieveTimerMessages;  

// Example of storing timer status in localStorage
// This can be called when the timer starts or pauses
function updateTimerStatus(timerId, status) {
    localStorage.setItem("timerMessage_" + timerId, status);
}
</script>











  





