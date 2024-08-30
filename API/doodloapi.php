<?php
$allowedOrigins = [
   'https://doodlodesigns.com',
];
 
if(in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins))
{
 $http_origin = $_SERVER['HTTP_ORIGIN'];
} else {
 $http_origin = "'https://doodlodesigns.com";
}
header("Access-Control-Allow-Origin: $http_origin");

echo"hii"; ?>
