<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

$servername = "3.23.209.253";
$username = "laravel";
$password = "z4sb4SkyVOVEBV2t";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>