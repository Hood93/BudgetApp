<?php
//Disabilitazione campo Transazione Ricorrenti

include "db.php";

$meseattuale = date('n') + 1;
$annoattuale = date('Y');

$query = "SELECT mesefinale,annofinale,idtransazioniricorrenti from transazioniricorrenti";

$ris = mysqli_query($conn,$query);

while ($row=mysqli_fetch_assoc($ris)){

    if(($row['mesefinale']+1) == $meseattuale){
        if ($row['annofinale'] == $annoattuale){
            $querydis = "UPDATE transazioniricorrenti SET Stato = 'Disattiva' WHERE idtransazioniricorrenti = '".$row["idtransazioniricorrenti"]."'";
        mysqli_query($conn,$querydis);
    } 
}
}
?>