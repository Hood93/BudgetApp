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
    echo "<td>" . "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalEditCategoria' class='btn btn-success btn-sm editbtn'><i class='fa-solid fa-pen-to-square fa-sm' style='color: #ffffff;'></i></button>" . "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";
?>
</tbody>
</table>

<script>
    $(document).ready(function() {
        //Modal Edit

        $('.editbtn').on('click', function() {

            $('#editmodal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            console.log(data);
            editidcategoria = data[0];
            editcategoria = data[2];

            $('#editidcategoria').val(editidcategoria);
            $('#editcategoria').val(editcategoria);
            //$('#editiconacategoria').val(editiconacategoria);

        });


    })
</script>