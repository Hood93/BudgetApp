<?php
//ini_set('display_errors', 1);
include "db.php";




$query = "SELECT * FROM descrizioneassociativa order by nuovadescrizione asc";


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
echo "<th scope='col'>Old Descrizione</th>";
echo "<th scope='col'>Nuova Descrizione</th>";
echo "<th scope='col'>Azione</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td style='display:none;'>" . $row["iddescrizioneassociativa"] . "</td>";
    echo "<td class='text-start'>" . $row['olddescrizione']. "</td>";
    echo "<td class='text-start'>" . $row['nuovadescrizione'] . "</td>";
    echo "<td>" . "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalEditDesAssociativa' class='btn btn-success btn-sm editbtndesass'><i class='fa-solid fa-pen-to-square fa-sm' style='color: #ffffff;'></i></button>" . "</td>";
    echo "</tr>";
}
echo "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalNewDesAssociativa' class='btn btn-success btn-sm addbtndesass'><i class='fa-solid fa-pen-to-square fa-sm' style='color: #ffffff;'></i></button>";
echo "</tbody>";
echo "</table>";
echo "</div>";
?>
</tbody>
</table>

<script>
    $(document).ready(function() {
        //Modal Edit

        $('.editbtndesass').on('click', function() {
            console.log ("descrizione associativa")
            //$('#editmodal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            console.log(data);
            editiddesassociativa = data[0];
            editolddescrizione = data[1];
            editnewdescrizione = data[2];

            $('#editiddesassociativa').val(editiddesassociativa);
            $('#editolddescrizione').val(editolddescrizione);
            $('#editnewdescrizione').val(editnewdescrizione);

        });


        //Modal Add

        $('.addbtndesass').on('click', function() {
            //$('#editmodal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            console.log(data);
            editiddesassociativa = data[0];
            editolddescrizione = data[1];
            editnewdescrizione = data[2];

            $('#editiddesassociativa').val(editiddesassociativa);
            $('#editolddescrizione').val(editolddescrizione);
            $('#editnewdescrizione').val(editnewdescrizione);

        });


    })
</script>