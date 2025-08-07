<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_DettaglioTransRicorrenti">
        <thead class="bg-light text-dark">
            <tr>
                <th scope="col">Descrizione</th>
                <th scope="col">Importo</th>
                <th scope="col">Tipo Carta</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //ini_set('display_errors', 1);
            include "db.php";
            $idtransamex = $_POST['idtransamex'];

            $query = "select  * from americanexpress WHERE idtransamex = '".$idtransamex."'";


            //Ciclo il risultato
            $result = mysqli_query($conn, $query);
            if (!$result) {
                echo "An error occurred.\n";
                exit;
            }

            while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["descrizioneamex"] . "</td>";
                    echo "<td> € " . $row["importoamex"] . "</td>";
                    echo "<td>" .$row['tipocarta']."</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</div>

