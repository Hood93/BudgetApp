<?php
//da rivedere
include "db.php";
$editiddesassociativa = $_POST['editiddesassociativa'];

if (empty($editiddesassociativa)) {
    //Inserimento nuova categoria


    $newolddescrizione = $_POST['newolddescrizione'];
    $newnewdescrizione = $_POST['newnewdescrizione'];
    $query = "INSERT INTO descrizioneassociativa(olddescrizione,nuovadescrizione) 
 VALUES('$newolddescrizione', '$newnewdescrizione')";
    
    if (mysqli_query($conn, $query)) {
        echo 'Data Inserted';
    }

    
} else {

    //Modifica Categoria

    $editiddesassociativa = $_POST['editiddesassociativa'];
    $editolddescrizione = $_POST['editolddescrizione'];
    $editnewdescrizione = $_POST['editnewdescrizione'];

    $query = "UPDATE descrizioneassociativa SET olddescrizione = '" . $editolddescrizione . "', nuovadescrizione = '".$editnewdescrizione."' WHERE iddescrizioneassociativa = " . $editiddesassociativa;
    if (mysqli_query($conn, $query)) {
        echo 'Data Inserted';
    }
}
