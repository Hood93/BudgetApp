<?php
ini_set('display_errors', '0');
$servername = (getenv('BUDGET_DB_HOST') !== false ? getenv('BUDGET_DB_HOST') : "10.147.1.249");
$username = (getenv('BUDGET_DB_USER') !== false ? getenv('BUDGET_DB_USER') : "root");
$password = (getenv('BUDGET_DB_PASSWORD') !== false ? getenv('BUDGET_DB_PASSWORD') : "root");
$dbname = (getenv('BUDGET_DB_NAME') !== false ? getenv('BUDGET_DB_NAME') : "budget");

  // Create connection
  $conn =  mysqli_connect($servername, $username, $password, $dbname);
    if ($conn) mysqli_set_charset($conn, 'utf8mb4');

    // Check connection
    if (!$conn) {

        error_log('BudgetApp database connection failed: ' . mysqli_connect_error());
        http_response_code(500);
        exit('Connessione al database non disponibile');
    }

?>

