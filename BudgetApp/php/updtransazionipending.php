<?php
include "db.php";

$editidoperazione = $_POST['editidoperazione'];
$editdataoperazione = $_POST['editdataoperazione'];
$editmese = $_POST['editmese'];
$editanno = $_POST['editanno'];
$editordinemese = $_POST['editordinemese'];
$editdescrizione = $_POST['editdescrizione'];
$edituscite = $_POST['edituscite'];
$editentrate = $_POST['editentrate'];
$edittag1 = $_POST['edittag1'];
$editcategoria = $_POST['editcategoria'];
$operazione = $_POST['operazione'];


if ($operazione == "inserimento"){
$query = "INSERT INTO budgettable(data_operazione,mese,anno,ordinemese, descrizione, uscite, entrate, tag1,categorie) 
VALUES('$editdataoperazione', '$editmese', '$editanno', '$editordinemese','$editdescrizione', '$edituscite', '$editentrate', '$edittag1','$editcategoria')";

if (mysqli_query($conn, $query)) {
    $querydel = "DELETE from transazionipending WHERE idtransazionipending = '".$editidoperazione."'";
    mysqli_query($conn, $querydel);
}
} else {
    $querydel = "DELETE from transazionipending WHERE idtransazionipending = '".$editidoperazione."'";
    mysqli_query($conn, $querydel);
}

?>