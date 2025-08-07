<?php
//ini_set('display_errors', 1);
include "../db.php";




$query = "SELECT ordinemese,mese, ROUND(SUM(uscite),2) as Uscite,ROUND(SUM(entrate),2) as Entrate FROM budget.budgettable";
$querytotann = "SELECT ROUND(SUM(uscite),2) as Uscite,ROUND(SUM(entrate),2) as Entrate FROM budget.budgettable";

if ((isset($_POST["cerca"]))) {
    $query .=
    " WHERE anno LIKE '%" . $_POST["cerca"] . "%' ";
$querytotann .=
    " WHERE anno LIKE '%" . $_POST["cerca"] . "%' ";
} else {
    $annoatt = date('Y');
    $query .=
        " WHERE anno LIKE '%" . $annoatt . "%' ";
    $querytotann .=
        " WHERE anno LIKE '%" . $annoatt . "%' ";

}


$query .= " group by mese, ordinemese order by ordinemese asc";

//Ciclo il risultato
$result = mysqli_query($conn, $query);
$resulttotann = mysqli_query($conn, $querytotann);
if (!$result) {
    echo "An error occurred.\n";
    exit;
}

while ($row = mysqli_fetch_assoc($result)) {
    $ris = $row["Entrate"] - $row["Uscite"];
    $arraygrafico[] = array('mese' => $row['mese'],'entrate' => $row['Entrate'],'uscite' => $row['Uscite'],'ris' => $ris);
    
}

echo json_encode($arraygrafico);

?>

