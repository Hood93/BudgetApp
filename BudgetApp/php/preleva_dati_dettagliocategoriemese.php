<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_DettaglioTransRicorrenti">
        <thead class="bg-light text-dark">
            <tr>
                <th scope="col">Data Operazione</th>
                <th scope="col">Descrizione</th>
                <th scope="col">Entrate</th>
                <th scope="col">Uscite</th>
                <th scope="col">Tag1</th>
                <th scope="col">Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php
             
            include "db.php";
            $datadioggi = date("d-m-Y");

            $categoria = $_POST['categoria'];
            $mese = $_POST['mese'];
            $anno = $_POST['anno'];

            $query = "select  * from budget.budgettable left join budget.categoriebudget on budget.budgettable.categorie = budget.categoriebudget.categoria WHERE mese = '".$mese."' and categorie = '".$categoria."' and anno = '".$anno."'";

           // if (!empty($cerca)) {
           //     $query .=
           //         " and anno = '".$cerca."' order by data_operazione asc";
           // } else {
           //     $annoatt = date('Y');
           //     $query .=
           //         " and anno = '".$annoatt."' order by data_operazione asc";
           // }

            //Ciclo il risultato
            $result = mysqli_query($conn, $query);
            if (!$result) {
                echo "An error occurred.\n";
                exit;
            }


            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['causale'] == "Iniziale") {
                } elseif (strtotime($datadioggi) < strtotime($row['data_operazione'])) {
                }else{
                    echo "<tr>";
                    echo "<td id='idbudgettable' style='display:none;'>" . $row["idBudgetTable"] . "</td>";
                    echo "<td>" . $row["data_operazione"] . "</td>";
                    echo "<td>" . $row["descrizione"] . "</td>";
                    echo "<td> € " . $row["entrate"] . "</td>";
                    echo "<td> € " . $row["uscite"] . "</td>";
                    echo "<td>" . $row["tag1"] . "</td>";
                    echo "<td>" . $row["icona"] . " " . $row["categorie"] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

