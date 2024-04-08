<?php
// Database credentials
$host = 'localhost'; // or your database host
$dbname = 'ballapo7_pmtool';
$username = 'ballapo7_pmtool';
$password = 'pmtool@2024';

// Create a MySQLi connection
$db = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
