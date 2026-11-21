<?php
ini_set('display_errors', '0');
$servername = "10.147.1.249";
$username = "root";
$password = "root";
$dbname = "budget";

  // Create connection
  $conn =  mysqli_connect($servername, $username, $password, $dbname);
    if ($conn) mysqli_set_charset($conn, 'utf8mb4');

    // Check connection
    if (!$conn) {

        die("Connection failed: " . mysqli_connect_error());
    }

?>

