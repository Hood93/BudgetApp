<?php

include "php/db.php";
include "php/utility.php";

$causale = "Iniziale";
$uscite = "0";
$entrate = "0";
$tag1 = "";
$anno = "2021";
//$anno = date("Y");


foreach($catarray as $categorie) {

for ($i = 1; $i <= 12; $i++) {
    if ($i < "10") {
        $data = $anno . "-0" . $i . "-01";
        switch ($i) {
            case "01":
                $mese = "Gennaio";
                $ordinemese = "1";
                break;
            case "02":
                $mese = "Febbraio";
                $ordinemese = "2";
                break;
            case "03":
                $mese = "Marzo";
                $ordinemese = "3";
                break;
            case "04":
                $mese = "Aprile";
                $ordinemese = "4";
                break;
            case "05":
                $mese = "Maggio";
                $ordinemese = "5";
                break;
            case "06":
                $mese = "Giugno";
                $ordinemese = "6";
                break;
            case "07":
                $mese = "Luglio";
                $ordinemese = "7";
                break;
            case "08":
                $mese = "Agosto";
                $ordinemese = "8";
                break;
            case "09":
                $mese = "Settembre";
                $ordinemese = "9";
                break;
            default:
                $mese = "";
        }
    } else {
        $data = $anno . "-" . $i . "-01";

        switch ($i) {
            case "10":
                $mese = "Ottobre";
                $ordinemese = "10";
                break;
            case "11":
                $mese = "Novembre";
                $ordinemese = "11";
                break;
            case "12":
                $mese = "Dicembre";
                $ordinemese = "12";
                break;
            default:
                $mese = "";
        }
    }
    $queryinstranscate = "INSERT INTO budgettable (data_operazione,mese,anno,ordinemese,causale,uscite,entrate,tag1,categorie) 
    VALUES('$data', '$mese', '$anno', '$ordinemese', '$causale', '$uscite', '$entrate', '$tag1', '$categorie')";
   mysqli_query($conn, $queryinstranscate);

    //echo $queryinstranscate;
}

}


?>