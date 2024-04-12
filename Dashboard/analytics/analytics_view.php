<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
?>
<?php require('../../API/analytics_api.php');

      require("../../API/db.php"); 
// $task_count = get_task_count($role, $eid, $db);
// $project_count = get_project_count($role, $eid, $db);
// $date = date("Y-m-d");

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Analytics</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Analytics</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-7">
                         <!-- Graphs Card start-->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Projects Graph</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Total projects</h6>
                            <canvas id="taskStatusChart" width="2200" height="1600"></canvas>
                         </div>
                    </div>
                   <!-- Graphs Card end -->
                    </div>
                </div>
            </div><!-- End Left side columns -->
        </div>
    </section>

    <script>
                        // Fetch data using AJAX
                        $.ajax({
                            url: "../../API/analytics_api.php",
                            method: 'GET',
                            success: function (data) {
                                var taskData = JSON.parse(data);

                                // Create chart using Chart.js
                                var ctx = document.getElementById('taskStatusChart').getContext('2d');
                                var taskChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: Object.keys(taskData),
                                        datasets: [{
                                            label: 'Task Status',
                                            data: Object.values(taskData),
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                                'rgba(255, 206, 86, 0.2)',
                                                // Add more colors as needed
                                            ],
                                            borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                'rgba(255, 206, 86, 1)',
                                                // Add more colors as needed
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            },
                            error: function (xhr, status, error) {
                                console.error('Error fetching data:', error);
                            }
                        });
                    </script>


</main><!-- End #main -->
<?php 
require('../footer.php');
?>