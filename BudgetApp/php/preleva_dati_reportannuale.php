<?php
//ini_set('display_errors', 1);
include "db.php";




$query = "SELECT ordinemese,mese, ROUND(SUM(uscite),2) as Uscite,ROUND(SUM(entrate),2) as Entrate FROM budget.budgettable";
$querytotann = "SELECT ROUND(SUM(uscite),2) as Uscite,ROUND(SUM(entrate),2) as Entrate FROM budget.budgettable";


if (isset($_POST["cerca"])) {
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

$query .= " group by mese, ordinemese order by ordinemese desc";

//Ciclo il risultato
$result = mysqli_query($conn, $query);
$resulttotann = mysqli_query($conn, $querytotann);
if (!$result) {
    echo "An error occurred.\n";
    exit;
}
echo "<div class='table table-responsive'>";
echo "<table class= 'table table-bordered border-black rounded-pill'>
<thead>
<tr>";
echo "<th scope='col'>Mese</th>";
echo "<th scope='col'>Entrate</th>";
echo "<th scope='col'>Uscite</th>";
echo "<th scope='col'>Differenza</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    $ris = $row['Entrate'] - $row['Uscite'];
    echo "<td class='text-start'>" . $row['mese'] . "</td>";
    echo "<td class='text-start'>" . $row['Entrate'] . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
    echo "<td class='text-start'>" . $row['Uscite'] . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
    if ($ris >= '0') {
        $color = "text-success";
    } else {
        $color = "text-danger";
    }
    echo "<td class='text-start ".$color."'>" . round($ris , 2) . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
    echo "</tr>";

}

echo "<td class='text-start'>Totale</td>";
while ($rowtotann = mysqli_fetch_assoc($resulttotann)) {
    $entratetotann = floatval($rowtotann['Entrate']);
    $uscitetotann = floatval($rowtotann['Uscite']);
    $ristotann = $entratetotann - $uscitetotann;
    if ($ristotann >= '0') {
        $color = "text-success";
    } else {
        $color = "text-danger";
    }
    echo "<td class='text-start'>" . $entratetotann . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
    echo "<td class='text-start'>" . $uscitetotann . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
    echo "<td class='text-start ".$color."'>" . round($ristotann,2) . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";
?>
</tbody>
</table>