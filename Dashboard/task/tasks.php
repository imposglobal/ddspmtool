<?php 
require('../header.php');
?>
<title>Dashboard - DDS</title>
<?php 
require('../sidebar.php');
require('../../API/function.php');
?>
<style>
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
.card-title {
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
</style>
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Tasks</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Tasks</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-6"></div>
        <div class="col-lg-6">
        <div class="float-right" style="float: right;">
        <button id="saveBtn" type="button" class="btn px-4 py-2 btn-md mb-4 text-white" style="background-color: #012970;"><i class="bi bi-file-earmark-plus-fill "></i> Add Task</button>
        </div>
        </div>
        
        <div class="col-lg-6">
        <form id="taskform">
          <div class="card">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card-body">
                    <h5 class="card-title">Start Date</h5>
                    <input type="date" id="sdate" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body">
                    <h5 class="card-title">End Date</h5>
                    <input type="date" id="edate" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-body">
                      <h5 class="card-title">Select Project</h5>
                      <select id="pname" class="form-select" aria-label="Default select example">
                        <option selected="">Select Project</option>
                        <?php 
                        $sql = "SELECT * FROM projects";
                        $result = mysqli_query($db, $sql);
                        if (mysqli_num_rows($result) > 0) {
                          while($row = mysqli_fetch_assoc($result)) {
                            echo'<option value="'.$row['pid'].'">'.$row['project_name'].'</option>';
                          }
                        }
                        ?>
                        
                      </select>
                   </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body">
                      <h5 class="card-title">Select Task Type</h5>
                      <select id="ttype" class="form-select" aria-label="Default select example">
                        <option selected="">Select Task Type</option>
                        <option value="Research">Research</option>
                        <option value="Original Project">Original Project</option>
                        <option value="Changes">Changes</option>
                      </select>
                   </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body">
                      <h5 class="card-title">Select Status</h5>
                      <select id="status" class="form-select" aria-label="Default select example">
                        <option selected="">Select Status</option>
                        <option value="Completed">Completed</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Pending">Pending</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Abonded">Abonded</option>
                      </select>
                   </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body">
                    <h5 class="card-title">Start Time</h5>
                    <input type="time" id="stime" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body">
                    <h5 class="card-title">End Time</h5>
                    <input type="time" id="etime" class="form-control">
                    </div>
                </div>
            </div>
          </div>

        </div>
        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
            <h5 class="card-title">Task Title</h5>
            <input type="text" id="title" class="form-control">
                <h5 class="card-title">Write Task Description</h5>
              <!-- TinyMCE Editor -->
              <textarea id="description" class="tinymce-editor">
               
              </textarea><!-- End TinyMCE Editor -->
              <input type="hidden" id="eid" value="<?php echo $eid; ?>" class="form-control">
            </div>
          </div>
          </form>
        </div>
      </div>
    </section>

  </main><!-- End #main -->
<?php 
require('../footer.php');
?>
<script>
   // Function to handle form submission with jQuery AJAX
$(document).ready(function() {
    $('#saveBtn').click(function(e) {
        e.preventDefault();
        var pname = $('#pname').val().trim(); // Trim whitespace
        var sdate = $('#sdate').val().trim();
        var edate = $('#edate').val().trim();
        var ttype = $('#ttype').val().trim();
        var status = $('#status').val().trim();
        var stime = $('#stime').val().trim();
        var etime = $('#etime').val().trim();
        var title = $('#title').val().trim();
        var eid = $('#eid').val().trim();
        var description = tinymce.get('description').getContent().trim(); // Trim whitespace

        if(pname !== "" && description !== "" && sdate !== "" && edate !== "" && ttype !== "" && status !== "" && stime !== "" && etime !== "" && title !== "") {
            // AJAX request
            $.ajax({
                type: "POST",
                url: "../../API/insert.php",
                data: { ops: 'task', pname: pname, description: description, sdate:sdate, edate:edate, ttype:ttype, status:status, stime:stime, etime:etime, title:title, eid:eid },
                success: function(response) {
                    // Use SweetAlert for displaying success message
                    Swal.fire({
                        icon: 'success',
                        title: title,
                        text: response
                    });
                    // Reset the form
                    $('#taskform').trigger('reset');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Use SweetAlert for displaying error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again later.'
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Required!',
                text: "Please fill all the fields"
            });
        }
    });
});
</script>
