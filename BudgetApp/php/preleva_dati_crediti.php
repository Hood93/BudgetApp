<?php
//ini_set('display_errors', 1);
include "db.php";




$query = "SELECT * FROM crediti";


//Ciclo il risultato
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "An error occurred.\n";
    exit;
}
echo "<div class='table table-responsive'>";
echo "<table class='table table-bordered border-black rounded-pill'>
<thead>
<tr>";
echo "<th scope='col'>Nome</th>";
echo "<th scope='col'>Descrizione</th>";
echo "<th scope='col'>Durata</th>";
echo "<th scope='col'>Importo</th>";
echo "<th scope='col'>Azione</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td style='display:none;'>" . $row["idcrediti"] . "</td>";
    echo "<td class='text-start'>" . $row['creditore']. "</td>";
    echo "<td class='text-start'>" . $row['descrizione']. "</td>";
    echo "<td class='text-start'>" . $row['durata'] . "</td>";
    echo "<td class='text-start'>" . $row['importo'] . "</td>";
    echo "<td  style='display:none;'>" . $row["idcreditore"] . "</td>";
    echo "<td>" . "<button class='btn mb-3' idcreditore = '".$row['idcreditore']."' onclick='apriCollapse(this)'  id='dettagliocredito'  type='button'><i class='fa-solid fa-circle-info fa-lg' style='color: #000000;'></i></button>" . "</td>";
    
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "<div class='collapse' id='showdettagliocredito'>";
?>
</tbody>
</table>
