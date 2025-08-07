<?php
ini_set('display_errors', 1);
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "budget";

  // Create connection
  $conn =  mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {

        die("Connection failed: " . mysqli_connect_error());
    }

?>

