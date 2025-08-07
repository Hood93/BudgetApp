<?php
include "php/db.php";

$query = "select categorie,idBudgetTable from budget.budgettable";

$result = mysqli_query($conn, $query);
if (!$result) {
    echo "An error occurred.\n";
    exit;
}

while ($row = mysqli_fetch_assoc($result)) {
    $data = $row['categorie'];
    //$data = explode("/",$data);
    //$giorno = $data[0];
    //$mese = $data[1];
    //$anno = $data[2];
    $data=strtolower($data);
    $data = ucwords($data);
    //$datafin = $anno."-".$mese."-".$giorno ;

    echo $data;
    echo "<br>";
    $querymod = "UPDATE budgettable SET categorie = '".$data."' WHERE idbudgettable = " .$row['idBudgetTable'];
   //mysqli_query($conn, $querymod);
}
echo $querymod;


 
?>