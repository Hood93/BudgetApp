<?php
//da rivedere
include "db.php";
$editidcatassociativa = $_POST['editidcatassociativa'];

if (empty($editidcatassociativa)) {
    //Inserimento nuova categoria


    $newdescrizioneass = $_POST['newdescrizioneass'];
    $newcategoriaassociata = $_POST['newcategoriaassociata'];
    $query = "INSERT INTO categoriaassociativa(descrizione,categoria) 
 VALUES('$newdescrizioneass', '$newcategoriaassociata')";
    
    if (mysqli_query($conn, $query)) {
        echo 'Data Inserted';
    }

    
} else {

    //Modifica Categoria

    $editidcatassociativa = $_POST['editidcatassociativa'];
    $editdescrizione = $_POST['editdescrizione'];
    $editcatassociativa = $_POST['editcatassociativa'];

    $query = "UPDATE categoriaassociativa SET descrizione = '" . $editdescrizione . "', categoria = '".$editcatassociativa."' WHERE idcategoriaassociativa = " . $editidcatassociativa;
    if (mysqli_query($conn, $query)) {
        echo 'Data Inserted';
    }
}
