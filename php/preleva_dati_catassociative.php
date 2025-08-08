<?php
//ini_set('display_errors', 1);
include "db.php";




$query = "SELECT * FROM categoriaassociativa order by categoria asc";


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
echo "<th scope='col'>Descrizione</th>";
echo "<th scope='col'>Categoria</th>";
echo "<th scope='col'>Azione</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td style='display:none;'>" . $row["idcategoriaassociativa"] . "</td>";
    echo "<td class='text-start'>" . $row['descrizione']. "</td>";
    echo "<td class='text-start'>" . $row['categoria'] . "</td>";
    echo "<td>" . "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalEditCatAssociativa' class='btn btn-success btn-sm editbtncatassociativa'><i class='fa-solid fa-pen-to-square fa-sm' style='color: #ffffff;'></i></button>" . "</td>";
    echo "</tr>";
}
echo "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalNewCatAssociativa' class='btn btn-success btn-sm addbtndcatass'><i class='fa-solid fa-pen-to-square fa-sm' style='color: #ffffff;'></i></button>";
echo "</tbody>";
echo "</table>";
echo "</div>";
?>
</tbody>
</table>

<script>
    $(document).ready(function() {
        //Modal Edit

        $('.editbtncatassociativa').on('click', function() {


            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            console.log(data);
            editidcatassociativa = data[0];
            editdescrizione = data[1];
            editcatassociativa = data[2];

            $('#editidcatassociativa').val(editidcatassociativa);
            $('#editdescrizione').val(editdescrizione);
            $('#editcatassociativa').val(editcatassociativa);

        });


    })
</script>