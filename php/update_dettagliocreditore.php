<?php
include "db.php";

$esito = $_POST['esito'];
$idcreditore = $_POST['idcreditore'];
$rata = $_POST['rata'];

echo $esito;
echo $idcreditore;
echo $rata;

$query = "UPDATE pianocrediti set checkpagato = '".$esito ."' WHERE idcreditore = '".$idcreditore."' AND rata = '".$rata."'";

mysqli_query($conn, $query);

?>