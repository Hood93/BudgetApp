<?php
//ini_set('display_errors', 1);
include "db.php";

$mese = $_POST['mese'];
$mese = $mese + 1;
$anno = $_POST['anno'];


$query = "SELECT round(sum(entrate),2) as Entrate,round(sum(uscite),2) as Uscite FROM budget.budgettable WHERE ordinemese = '" . $mese . "' and anno = '" . $anno . "'";

switch ($mese) {
  case "1":
    $mesetext = "Gennaio";
    break;
  case "2":
    $mesetext = "Febbraio";
    break;
  case "3":
    $mesetext = "Marzo";
    break;
  case "4":
    $mesetext = "Aprile";
    break;
  case "5":
    $mesetext = "Maggio";
    break;
  case "6":
    $mesetext = "Giugno";
    break;
  case "7":
    $mesetext = "Luglio";
    break;
  case "8":
    $mesetext = "Agosto";
    break;
  case "9":
    $mesetext = "Settembre";
    break;
  case "10":
    $mesetext = "Ottobre";
    break;
  case "11":
    $mesetext = "Novembre";
    break;
  case "12":
    $mesetext = "Dicembre";
    break;
  default:
    $mesetext = "";
}




//Ciclo il risultato
$result = mysqli_query($conn, $query);

if (!$result) {
  echo "An error occurred.\n";
  exit;
}
echo "<div class='table'>";
echo "<table class= 'table table-bordered border-black'>
<thead>
<tr>";
echo "<i class='fa-solid fa-chart-line fa-xl' style='color: #000000;'></i> " . $mesetext . " " . $anno;
echo "</tr>";
echo "<tr>";
echo "<th scope='col'>Entrate</th>";
echo "<th scope='col'>Uscite</th>";
echo "<th scope='col'>Differenza</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>";
  $ris = $row['Entrate'] - $row['Uscite'];
  echo "<td class='text-start'>" . $row['Entrate'] . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
  echo "<td class='text-start'>" . $row['Uscite'] . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
  echo "<td class='text-start'>" . round($ris,2) . " " . "<i class='fa-solid fa-euro-sign fa-xs' style='color: #000000;'></i></td>";
  echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";
?>
</tbody>
</table>