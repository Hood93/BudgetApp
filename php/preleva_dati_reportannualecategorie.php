<?php
//ini_set('display_errors', 1);
include "db.php";
include "utility.php";

$meseprec = "";
$colcat = 1;




$querycat = "SELECT ordinemese,mese,categorie,icona, ROUND(SUM(uscite),2) as Uscite,ROUND(SUM(entrate),2) as Entrate FROM budget.budgettable left join budget.categoriebudget on budget.budgettable.categorie = budget.categoriebudget.categoria";
$querytotcat = "SELECT categorie,icona, ROUND(SUM(uscite),2) as Uscite,ROUND(SUM(entrate),2) as Entrate FROM budget.budgettable left join budget.categoriebudget on budget.budgettable.categorie = budget.categoriebudget.categoria";




if (isset($_POST["cerca"])) {
    $querycat .=
        " WHERE anno LIKE '%" . $_POST["cerca"] . "%'";
    $querytotcat .=
        " WHERE anno LIKE '%" . $_POST["cerca"] . "%'";
    $anno = $_POST["cerca"];
} else {
    $annoatt = date('Y');
    $querycat .=
        " WHERE anno LIKE '%" . $annoatt . "%'";
    $querytotcat .=
        " WHERE anno LIKE '%" . $annoatt . "%'";
    $anno = $annoatt;
}

$querycat .= " group by mese, ordinemese,categorie,icona order by ordinemese,categorie asc";

$querytotcat .=  "  group by  categorie,icona order by categorie asc";


//Ciclo il risultato
$result = mysqli_query($conn, $querycat);
$resultcat = mysqli_query($conn, $querytotcat);

if (!$result) {
    echo "An error occurred.\n";
    exit;
}
echo "<div class='table table-responsive'>";
echo "<table id='rescate' class='table table-bordered'>
<tr>
<th>Mese</th>";
foreach ($catarray as $categoria) {

    echo   "<th>" . $categoria . "</th>";
}
echo "</tr>";



while ($row = mysqli_fetch_assoc($result)) {


    if ($meseprec == $row['mese']) {
        $colcat = $colcat + 1;
        $entratecat = floatval($row['Entrate']);
        $uscitecat = floatval($row['Uscite']);
        $ris = $entratecat - $uscitecat;
        echo "<td class='text-start' onclick='Dettaglio(this)' anno='" . $anno . "'  colcat ='" . $colcat . "'>" . $ris . "</td>";
    } else {
        $colcat = 1;
        $entratecat = floatval($row['Entrate']);
        $uscitecat = floatval($row['Uscite']);
        echo "<tr>";
        echo "<td class='text-start'>" . $row['mese'] . "</td>";
        $ris = $entratecat - $uscitecat;
        echo "<td class='text-start' onclick='Dettaglio(this)' anno='" . $anno . "'  colcat ='" . $colcat . "'>" . $ris . "</td>";
    }
    $meseprec = $row['mese'];
}

echo "</tr>";
echo "<td class='text-start'>Totale</td>";
while ($rowmeseto = mysqli_fetch_assoc($resultcat)) {
    $entratetotmese = floatval($rowmeseto['Entrate']);
    $uscitetotmese = floatval($rowmeseto['Uscite']);
    $ristotmese = $entratetotmese - $uscitetotmese;
    echo "<td class='text-start'>" . $ristotmese . "</td>";
}



echo "</table>";
echo "</div>";
?>
</tbody>
</table>






<script>
    $(document).ready(function() {
        $('#VistaDettaglioCategorieMese').on('hidden.bs.modal', function(e) {
            $('#dettaglioamex').collapse('hide')
        })
    })

    function Dettaglio(_this) {

        $('#VistaDettaglioCategorieMese').modal('show');
        var td = $(_this).closest('tr');
        var mese = td.find('td:first').text()
        var myTable = document.getElementById('rescate');
        var rows = myTable.rows;
        var firstRow = rows[0];
        var data = $(firstRow).find("th").each(function() {});
        var colcat = $(_this).attr('colcat');
        var anno = $(_this).attr('anno');
        var categoria = data[colcat].innerHTML

        $('#viewcategoriadettaglio').text(categoria);

        if (categoria == 'Cdc') {
            document.getElementById('insertamex').style.visibility = 'visible'
            document.getElementById('dettamex').style.visibility = 'visible'
        } else {
            document.getElementById('insertamex').style.visibility = 'hidden'
            document.getElementById('dettamex').style.visibility = 'hidden'
        }

        $.ajax({
            type: 'POST',
            url: 'php/preleva_dati_dettagliocategoriemese.php',
            data: {
                ajax: 1,
                categoria: categoria,
                mese: mese,
                anno: anno

            },
            success: function(response) {
                $("#dettagliocategoriemese").html(response);
            }
        });


    }
</script>