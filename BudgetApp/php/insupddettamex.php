<?php

include "db.php";
//$editidoperazione = $_POST['editidoperazione'];

//if (empty($editidoperazione)) {
    $idtransamex = $_POST['idtransamex'];
    $descrizioneamex = $_POST['descrizioneamex'];
    $importoamex = $_POST['importoamex'];
    $newtipocartaamex = $_POST['newtipocartaamex'];



    $query = "INSERT INTO americanexpress(descrizioneamex,importoamex,idtransamex,tipocarta) 
 VALUES('$descrizioneamex', '$importoamex', '$idtransamex','$newtipocartaamex')";
   
    mysqli_query($conn, $query);
        
    
//} else {
  //  $editdataoperazione = $_POST['editdataoperazione'];
  //  $editmese = $_POST['editmese'];
  //  $editanno = $_POST['editanno'];
  //  $editordinemese = $_POST['editordinemese'];
    //$editcausale = $_POST['editcausale'];
  //  $editdescrizione = $_POST['editdescrizione'];
  //  $edituscite = $_POST['edituscite'];
  //  $editentrate = $_POST['editentrate'];
  //  $edittag1 = $_POST['edittag1'];
  //  $editcategoria = $_POST['editcategoria'];
    //$edittag3 = $_POST['edittag3'];
    //$edittag4 = $_POST['edittag4'];

 //   $query = "UPDATE budgettable SET data_operazione = '" . $editdataoperazione . "', 
 //   mese = '" . $editmese . "', 
 //   anno = '" . $editanno . "', 
 //   ordinemese = '" . $editordinemese . "',
 //   descrizione = '" . $editdescrizione . "',
 //   uscite = '" . $edituscite . "',
 //   entrate = '" . $editentrate . "',
 //   tag1 = '" . $edittag1 . "',
 //   categorie = '" . $editcategoria . "' WHERE idbudgettable = " .$editidoperazione;
 //   if (mysqli_query($conn, $query)) {
 //       echo 'Data Inserted';
 //   }
//}
