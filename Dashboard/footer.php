<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>DDS</span></strong>. All Rights Reserved
    </div>
   
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="https://dds.doodlo.in/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/echarts/echarts.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/quill/quill.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="https://dds.doodlo.in/assets/vendor/php-email-form/validate.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert library -->


  <!-- Template Main JS File -->
  <script src="https://dds.doodlo.in/assets/js/main.js"></script>
<script>
  $(document).ready(function(){
    $('#clockout').hide();
    // Function to check data and hide elements
    function checkAndHide() {
        $.ajax({
            url: "<?php echo $base_url; ?>/API/attendance.php",
            method: "POST",
            data: { ops: 'checkatt', eid:<?php echo $eid; ?> },
            success: function(response) {
                // Assuming data is retrieved successfully
                // You may need to modify the condition based on your data
                if (response === 'clockin') {
                    $('#clockin').hide(); // Hide elements with class 'jquy'
                    $('#clockout').show();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }

    // Call the function on document load
    checkAndHide();
});
</script>
  <!-- Ajax Request for Clockin -->
<script>
  function clockin() 
  {
      var eid = '<?php echo $eid ?>';    
      $.ajax({
      type: 'POST', 
      url: '../API/attendance.php', 
      data: 
      { 
        ops: 'clockin', 
        eid: eid
      },
      success: function(response) { 
        console.log('Data sent successfully!');
        window.location.reload();
      },
      error: function(xhr, status, error) { 
        console.error('Error occurred while sending data:', error);
      }
    });
  }
</script>

<!-- Ajax Request for Clockout -->

<script>
  function clockout() {
    var eid = '<?php echo $eid ?>';    
    // AJAX request
      $.ajax({
      type: 'POST', 
      url: '../API/attendance.php', 
      data: { 
        ops: 'clockout', 
        eid: eid
      },
      success: function(response) { 
        console.log('Data sent successfully!');
        window.location.reload();
      },
      error: function(xhr, status, error) { 
        console.error('Error occurred while sending data:', error);
      }
    });
  }
</script>







</body>

</html>