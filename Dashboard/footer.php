 <style>
  .lightbox {
            display: none;
            position: fixed;
            z-index: 1;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .box {
            position: absolute;
            padding: 50px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #012970;
            color: #fff;
        }

        h2, .box p {
            margin: 0 20px;
        }

        .close {
            position: absolute;
            right: 10px;
            top: 10px;
            bottom:30px;
            width: 20px;
            height: 20px;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            border-radius: 50%;
            background-color: #5c5c5c;
            cursor: pointer;
            text-decoration: none;
        } 


    
</style> 
<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>DDS</span></strong>. All Rights Reserved
    </div>
   
  </footer><!-- End Footer -->

  <!-- alert popup -->
  <div class="lightbox">
        <div class="box">
            <h4> Before closing this window, please ensure you have completed the following tasks:</h2>
            <h2>1) Add your task.</h2>
            <h2>2) Resume task.</h2>
            <h2>3) Clock out.</h2>
            <a href="#" class="close">X</a>
        </div>
  </div>

  
  <!-- alert popup -->

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
    // Reload the window every 10 minutes (600,000 milliseconds)
    setTimeout(function() {
    window.location.reload();
    }, 600000);
  </script>

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
function clockout() 
{
  var eid = '<?php echo $eid ?>';
  // AJAX request
  $.ajax({
    type: 'POST',
    url: '../API/attendance.php',
    data: {
      ops: 'clockout',
      eid: eid
    },
    success: function(response) 
    {
      var result = JSON.parse(response);
      if (result.status === 'success') 
      {
        console.log('Data sent successfully!');
        window.location.reload();
      } 
      else 
      {
        Swal.fire({
        icon: 'warning',
        title: 'Warning',
        text: result.message,
        });
      }
    },
    error: function(xhr, status, error) {
      console.error('Error occurred while sending data:', error);
    }
  });
}
</script>
<?php  
if($role == 1)
{
?>
 <script>
        function addEvent(obj, evt, fn) {
            if (obj.addEventListener) {
                obj.addEventListener(evt, fn, false);
            } else if (obj.attachEvent) {
                obj.attachEvent("on" + evt, fn);
            }
        }

        addEvent(document, 'mouseleave', function(evt) {
            if (evt.clientY <= 0) {
                $('.lightbox').slideDown();
            }
        });

        $('a.close').click(function() {
            $('.lightbox').slideUp();
        });
    </script>

<?php
}
?>

<!-- tab closing -->






   

    
</body>
</html>