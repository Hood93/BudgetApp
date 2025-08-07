<?php
include "db.php";

$delidtransricorrenti = $_POST['delidtransricorrenti'];


$querybudget = "DELETE from budgettable where idtransazioniricorrenti  = '".$delidtransricorrenti."' and data_operazione >= date(now())";
$querytransric = "DELETE from transazioniricorrenti where idtransazioniricorrenti  = '".$delidtransricorrenti."'";

mysqli_query($conn,$querybudget);
mysqli_query($conn,$querytransric);

?>
