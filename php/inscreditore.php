<?php
include "db.php";
include "utility.php";

$newnomecreditore = $_POST['newnomecreditore'];
$newduratacreditore = $_POST['newduratacreditore'];
$newimportocreditore = $_POST['newimportocreditore'];
$newdescrizionecreditore = $_POST['newdescrizionecreditore'];
$newidcreditore = uniqid();



$query = "INSERT INTO crediti(creditore,descrizione, durata,importo,idcreditore) 
VALUES('$newnomecreditore', '$newdescrizionecreditore', '$newduratacreditore', '$newimportocreditore','$newidcreditore')";
  
   mysqli_query($conn, $query);

$rata = intval($newimportocreditore) / intval($newduratacreditore);
for ($i=1;$i <= intval($newduratacreditore) ; $i++ ){
    $query = "INSERT INTO pianocrediti(creditore,descrizione,rata,importo,idcreditore) 
    VALUES('$newnomecreditore', '$newdescrizionecreditore' ,'$i', '$rata','$newidcreditore')";
    mysqli_query($conn, $query);
}

?>