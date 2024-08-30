<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if(isset($_POST['name'])){
  $name = $_POST['name'];
  echo $name;
}
 ?>
