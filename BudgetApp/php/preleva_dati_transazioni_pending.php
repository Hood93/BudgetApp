<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_TransazioniTotali">
        <thead class="bg-light text-dark">
            <tr>
                <th scope="col">Data Operazione</th>
                <th scope="col">Descrizione</th>
                <th scope="col">DescrizioneOld</th>
                <th scope="col">Entrate</th>
                <th scope="col">Uscite</th>
                <th scope="col">Categoria</th>
                <th scope="col">Tag1</th>
                <th scope="col">Seleziona</th>
                <th scope="col">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //ini_set('display_errors', 1);
            include "db.php";


            $query = "select  * from budget.transazionipending";

            if (isset($_POST["categoria"])) {
                $query .=
                    " WHERE categorie LIKE '%" . $_POST["categoria"] . "%'";
            }


            $query .= " order by data_operazione desc";



            //Ciclo il risultato
            $result = mysqli_query($conn, $query);
            if (!$result) {
                echo "An error occurred.\n";
                exit;
            }

            while ($row = mysqli_fetch_assoc($result)) {

                if ($row['causale'] == "Iniziale") {
                } else {
                    $tranpending = $row["idtransazionipending"] . ";" . $row["data_operazione"] . ";" . $row["mese"] . ";" . $row["anno"] . ";" . $row["ordinemese"] . ";" . $row["descrizione"] . ";" . $row["entrate"] . ";" . $row["uscite"] . ";" . $row["categorie"] . ";" . $row["tag1"];
                    echo "<tr>";
                    echo "<td style='display:none;'>" . $row["idtransazionipending"] . "</td>";
                    echo "<td>" . $row["data_operazione"] . "</td>";
                    echo "<td style='display:none;'>" . $row["mese"] . "</td>";
                    echo "<td style='display:none;'>" . $row["anno"] . "</td>";
                    echo "<td style='display:none;'>" . $row["ordinemese"] . "</td>";
                    echo "<td>" . $row["descrizione"] . "</td>";
                    echo "<td>" . $row["descrizioneold"] . "</td>";
                    echo "<td> € " . $row["entrate"] . "</td>";
                    echo "<td> € " . $row["uscite"] . "</td>";
                    echo "<td>" . $row["categorie"] . "</td>";
                    echo "<td>" . $row["tag1"] . "</td>";
                    echo "<td><div class='form-check'><input class='form-check-input' type='checkbox' value='" . $tranpending . "' id='flexCheckDefault' onclick='Aggiungialblocco(this)' disabled></div></td>";
                    echo "<td>" . "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalEditTransazioniPending' class='btn btn-success editbtn'><span><i class='fa-solid fa-pen-to-square'></i></span></button>" . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {

        $('.editbtn').on('click', function() {

            $('#ModalEditTransazioniPending').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            console.log(data);
            editidoperazione = data[0];
            editdataoperazione = data[1].replaceAll('/', '-');
            editmese = data[2];
            editanno = data[3];
            editordinemese = data[4];
            editdescrizione = data[5];
            editentrate = data[7];
            edituscite = data[8];
            editentrate = editentrate.replace("€", "");
            edituscite = edituscite.replace("€", "");
            editentrate = editentrate.replace(/\s+/g, '');
            edituscite = edituscite.replace(/\s+/g, '');
            editcategoria = data[9];
            edittag1 = data[10];

            $('#editidoperazione').val(editidoperazione);
            $('#editdataoperazione').val(editdataoperazione);
            $('#editmese').val(editmese);
            $('#editanno').val(editanno);
            $('#editordinemese').val(editordinemese);
            $('#editdescrizione').val(editdescrizione);
            $('#editentrate').val(editentrate);
            $('#edituscite').val(edituscite);
            $('#editcategoria').val(editcategoria);
            $('#edittag1').val(edittag1);


        });
    });

    //var arraytranpendmod = [];

    function Aggiungialblocco(_this) {
        var ischecked = _this.checked
        var elementi = $(_this).val();
        if (ischecked == true) {
            arraytranpendmod.push(elementi)
         } else {
           let index = arraytranpendmod.indexOf(elementi);
           arraytranpendmod.splice(index,1)
        }
    }   
</script>