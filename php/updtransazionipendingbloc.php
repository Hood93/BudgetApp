<?php
include "db.php";

$arraytranpendmod = $_POST['arraytranpendmod'];
$operazione = $_POST['operazione'];

print_r($arraytranpendmod);


if ($operazione == "inserimento"){
    foreach($arraytranpendmod as $tranpend){
    $arraytranpendmodapp = explode(";",$tranpend);
    $editidoperazione = $arraytranpendmodapp[0];
    $editdataoperazione = $arraytranpendmodapp[1];
    $editmese = $arraytranpendmodapp[2];
    $editanno = $arraytranpendmodapp[3];
    $editordinemese = $arraytranpendmodapp[4];
    $editdescrizione = $arraytranpendmodapp[5];
    $editentrate = $arraytranpendmodapp[6];
    $edituscite = $arraytranpendmodapp[7];
    $editcategoria = $arraytranpendmodapp[8];
    $edittag1 = $arraytranpendmodapp[9];
    $query = "INSERT INTO budgettable(data_operazione,mese,anno,ordinemese, descrizione, uscite, entrate, tag1,categorie) 
    VALUES('$editdataoperazione', '$editmese', '$editanno', '$editordinemese','$editdescrizione', '$edituscite', '$editentrate', '$edittag1','$editcategoria')";
        if  (mysqli_query($conn, $query)) {
            $querydel = "DELETE from transazionipending WHERE idtransazionipending = '".$editidoperazione."'";
            mysqli_query($conn, $querydel);
        }    
    }
} else {
    foreach($arraytranpendmod as $tranpend){
    $arraytranpendmodapp = explode(";",$tranpend);
    $editidoperazione = $arraytranpendmodapp[0];
    $querydel = "DELETE from transazionipending WHERE idtransazionipending = '".$editidoperazione."'";
    mysqli_query($conn, $querydel);
    }
}

?>