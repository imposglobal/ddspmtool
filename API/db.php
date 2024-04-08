<?php
// Database credentials
$host = 'localhost'; // or your database host
$dbname = 'doodlueg_task';
$username = 'root';
$password = '';

// Create a MySQLi connection
$db = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
