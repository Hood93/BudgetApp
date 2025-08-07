<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "budget";


$newdataoperazione = $_POST['newdataoperazione'];
$newmese = $_POST['newmese'];
$newanno = $_POST['newanno'];
$newordinemese = $_POST['newordinemese']; 
$newdescrizione = $_POST['newdescrizione'];
$newuscite = $_POST['newuscite'];
$newentrate = "0";
$newtag1 = $_POST['newtag1'];
$newcategoria = $_POST['newcategoria'];

  // Create connection
  $conn =  mysqli_connect($servername, $username, $password, $dbname);

$query = "INSERT INTO budgettable(data_operazione,mese,anno,ordinemese, descrizione, uscite, entrate, tag1,categorie) 
 VALUES('$newdataoperazione', '$newmese', '$newanno', '$newordinemese','$newdescrizione', '$newuscite', '$newentrate', '$newtag1','$newcategoria')";
    echo $query;
    if (mysqli_query($conn, $query)) {
        echo 'Data Inserted';
    }

