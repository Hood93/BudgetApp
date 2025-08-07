<?php

include "db.php";
$editidoperazione = $_POST['editidoperazione'];

if (empty($editidoperazione)) {
    $newdataoperazione = $_POST['newdataoperazione'];
    $newmese = $_POST['newmese'];
    $newanno = $_POST['newanno'];
    $newordinemese = $_POST['newordinemese'];
    //$newcausale = $_POST['newcausale'];
    $newdescrizione = $_POST['newdescrizione'];
    $newuscite = $_POST['newuscite'];
    $newentrate = $_POST['newentrate'];
    $newtag1 = $_POST['newtag1'];
    $newcategoria = $_POST['newcategoria'];
    //$newtag3 = $_POST['newtag3'];
    //$newtag4 = $_POST['newtag4'];


    $query = "INSERT INTO budgettable(data_operazione,mese,anno,ordinemese, descrizione, uscite, entrate, tag1,categorie) 
 VALUES('$newdataoperazione', '$newmese', '$newanno', '$newordinemese','$newdescrizione', '$newuscite', '$newentrate', '$newtag1','$newcategoria')";
    echo $query;
    if (mysqli_query($conn, $query)) {
        echo 'Data Inserted';
    }
} else {
    $editdataoperazione = $_POST['editdataoperazione'];
    $editmese = $_POST['editmese'];
    $editanno = $_POST['editanno'];
    $editordinemese = $_POST['editordinemese'];
    //$editcausale = $_POST['editcausale'];
    $editdescrizione = $_POST['editdescrizione'];
    $edituscite = $_POST['edituscite'];
    $editentrate = $_POST['editentrate'];
    $edittag1 = $_POST['edittag1'];
    $editcategoria = $_POST['editcategoria'];
    //$edittag3 = $_POST['edittag3'];
    //$edittag4 = $_POST['edittag4'];

    $query = "UPDATE budgettable SET data_operazione = '" . $editdataoperazione . "', 
    mese = '" . $editmese . "', 
    anno = '" . $editanno . "', 
    ordinemese = '" . $editordinemese . "',
    descrizione = '" . $editdescrizione . "',
    uscite = '" . $edituscite . "',
    entrate = '" . $editentrate . "',
    tag1 = '" . $edittag1 . "',
    categorie = '" . $editcategoria . "' WHERE idbudgettable = " .$editidoperazione;
    if (mysqli_query($conn, $query)) {
        echo 'Data Inserted';
    }
}
