<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_DettaglioCredito">
        <thead class="bg-light text-dark">
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Descrizione</th>
                <th scope="col">Rata</th>
                <th scope="col">Importo</th>
                <th scope="col">Check</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //ini_set('display_errors', 1);
            include "db.php";
            $idcreditore = $_POST['idcreditore'];

            $query = "select  * from pianocrediti WHERE idcreditore = '".$idcreditore."'";


            //Ciclo il risultato
            $result = mysqli_query($conn, $query);
            if (!$result) {
                echo "An error occurred.\n";
                exit;
            }

            while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["creditore"] . "</td>";
                    echo "<td>" .$row['descrizione']."</td>";
                    echo "<td>" . $row["rata"] . "</td>";
                    echo "<td> € " . $row["importo"] . "</td>";
                    echo "<td style='display:none;'>" . $row["idcreditore"] . "</td>";
                    echo "<td> <div class='form-check form-switch'><input class='form-check-input' idcreditore = '".$row['idcreditore']."' rata = '".$row['rata']."' type='checkbox' role='switch' onclick='updateCheckPagato(this)'".$row['checkpagato']." ></div></td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</div>

<script>
        function updateCheckPagato(_this) {
       
        var idcreditore = $(_this).attr("idcreditore")
        var rata = $(_this).attr("rata")
        if($(_this).is(':checked')){
            esito = "checked"
        } else {
            esito = ""
        }
        $.ajax({
            type: 'POST',
            url: './php/update_dettagliocreditore.php',
            data: {
                ajax: 1,
                idcreditore:idcreditore,
                rata: rata,
                esito: esito

            },
            success: function(response) {
                location.reload()
            }
        });
    }
</script>

