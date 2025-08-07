<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_TransazioniTotali">
        <thead class="bg-light text-dark">
            <tr>
                <!--<th scope="col">Data Operazione</th>
                <th scope="col">Causale</th>
                <th scope="col">Descrizione</th>
                <th scope="col">Entrate</th>
                <th scope="col">Uscite</th>
                <th scope="col">Tag1</th>
                <th scope="col">Categorie</th>
                <th scope="col">Tag3</th>
                <th scope="col">Tag4</th>
                <th scope="col">Azioni</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            //ini_set('display_errors', 1);
            include "db.php";

            $datadioggi = date("d-m-Y");




            //$query = "SELECT * FROM budgettable ";
            $query = "select  * from budget.budgettable left join budget.categoriebudget on budget.budgettable.categorie = budget.categoriebudget.categoria";




            if (isset($_POST["cerca"])) {
                $query .=
                    " WHERE data_operazione LIKE '%" . $_POST["cerca"] . "%' 
                            OR causale LIKE '%" . $_POST["cerca"] . "%' 
                            OR descrizione LIKE '%" . $_POST["cerca"] . "%' 
                            OR entrate LIKE '%" . $_POST["cerca"] . "%' 
                            OR uscite LIKE '%" . $_POST["cerca"] . "%'
                            OR tag1 LIKE '%" . $_POST["cerca"] . "%'
                            OR categorie LIKE '%" . $_POST["cerca"] . "%'
                            OR tag3 LIKE '%" . $_POST["cerca"] . "%'
                            OR tag4 LIKE '%" . $_POST["cerca"] . "%'";
            }
            if (isset($_POST["anno"])) {
                $query .=
                    " WHERE anno LIKE '%" . $_POST["anno"] . "%'
                AND mese LIKE '%" . $_POST["mese"] . "%'
                AND tag1 LIKE '%" . $_POST["tag1"] . "%'
                    AND categoria LIKE '%" . $_POST["categoria"] . "%'";
            } else if (isset($_POST["mese"])) {
                $query .=
                    " WHERE mese LIKE '%" . $_POST["mese"] . "%'
                AND anno LIKE '%" . $_POST["anno"] . "%'
                AND tag1 LIKE '%" . $_POST["tag1"] . "%'
                AND categoria LIKE '%" . $_POST["categoria"] . "%'";
            } elseif (isset($_POST["categoria"])) {
                " WHERE categoria LIKE '%" . $_POST["categoria"] . "%'
                AND anno LIKE '%" . $_POST["anno"] . "%'
                AND tag1 LIKE '%" . $_POST["tag1"] . "%'
                AND mese LIKE '%" . $_POST["mese"] . "%'";
            } elseif (isset($_POST["tag1"])) {
                " WHERE tag1 LIKE '%" . $_POST["tag1"] . "%'
                AND anno LIKE '%" . $_POST["anno"] . "%'
                AND categoria LIKE '%" . $_POST["categoria"] . "%'
                AND mese LIKE '%" . $_POST["mese"] . "%'";
            }

            $query .= " order by data_operazione desc";



            //Ciclo il risultato
            $result = mysqli_query($conn, $query);
            if (!$result) {
                echo "An error occurred.\n";
                exit;
            }


            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['tag1'] == "Uscita"){
                    $cardcolor = " text-bg-danger ";
                    $valore€ = $row["uscite"];
                } else {
                    $cardcolor = " text-bg-success ";
                    $valore€ = $row["entrate"];
                }

                if ($row['causale'] == "Iniziale") {
                } elseif (strtotime($datadioggi) < strtotime($row['data_operazione'])) {
                } else {
                    $datojson = $row["idBudgetTable"] . "," . $row["data_operazione"] . "," . $row["descrizione"] .",". $row['entrate'] . ",". $row['uscite'] . "," . $row["tag1"] . "," .$row["categorie"];
                     echo "<tr>";
                        //echo "<td style='display:none;'>" . $row["idBudgetTable"] . "</td>";
                        //echo "<td>" . $row["data_operazione"] . "</td>";
                    //echo "<td>" . $row["causale"] . "</td>";
                        //echo "<td>" . $row["descrizione"] . "</td>";
                        //echo "<td> € " . $row["entrate"] . "</td>";
                        //echo "<td> € " . $row["uscite"] . "</td>";
                        //echo "<td>" . $row["tag1"] . "</td>";
                        //echo "<td>" . $row["icona"] . " " . $row["categorie"] . "</td>";
                    //echo "<td>" . $row["tag3"] . "</td>";
                    //echo "<td>" . $row["tag4"] . "</td>";
                        //echo "<td>" . "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalEditOperazione' class='btn btn-success editbtn'><span><i class='fa-solid fa-pen-to-square'></i></span></button>" . "</td>";

                    echo "<div class='card h-25".$cardcolor." mb-3'>";
                        echo "<div class='row g-0'>";
                            echo "<div class='col immcat text-center'>";
                            echo "<h5 class='card-title icocard'>". $row["icona"] ."</h5>";
                            echo "</div>";
                            echo "<div class='col'>";
                               echo "<div class='card-body'>";
                                    echo "<h5 class='card-title catecard'>". $row["categorie"] ."</h5>";
                                    echo "<p class='card-text'>";
                                    echo "<div class='scroll'><h5 class='desccard'>".$row["descrizione"]."</h5></div>";
                                    echo "</p>";
                                    echo "<p class='card-text'><small class='text-body-secondary'>".$row["data_operazione"]."</small></p>";
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='col valorecard text-center'> € ".$valore€."</div>";
                            echo "<div class='row'>";
                            echo "<div class='col bottcard text-center'><button type='button' data-bs-toggle='modal' data-bs-target='#ModalEditOperazione' class='btn btn-dark editbtn' onclick=EditOperazione(this) data='".$datojson."'><span><i class='fa-solid fa-pen-to-square'></i></span></button></div>";
                            echo "</div>";
                            echo "</div>";
                    echo "</div>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>





<script>
    $(document).ready(function() {

        /*$('.editbtn').on('click', function() {

            $('#editmodal').modal('show');

            //$tr = $(this).closest('tr');
            //var data = $tr.children("td").map(function() {
            //    return $(this).text();
            //}).get();
            var data = $('#btnedit').attr('data')
            console.log(data)
            data = data.split(',')

 
            editidoperazione = data[0];
            editdataoperazione = data[1].replaceAll('/', '-');
            //editcausale = data[2];
            editdescrizione = data[2];
            editentrate = data[3];
            edituscite = data[4];
            //editentrate = editentrate.replace("€", "");
            //edituscite = edituscite.replace("€", "");
            //editentrate = editentrate.replace(/\s+/g, '');
            //edituscite = edituscite.replace(/\s+/g, '');
            edittag1 = data[5];
            editcategoria = data[6];
            //edittag3 = data[8];
            //edittag4 = data[9];

            $('#editidoperazione').val(editidoperazione);
            $('#editdataoperazione').val(editdataoperazione);
            //$('#editcausale').val(editcausale);
            $('#editdescrizione').val(editdescrizione);
            $('#editentrate').val(editentrate);
            $('#edituscite').val(edituscite);
            $('#edittag1').val(edittag1);
            $('#editcategoria').val(editcategoria);
            //$('#edittag3').val(edittag3);
            //$('#edittag4').val(edittag4);
        });*/
    });


    function EditOperazione(_this){
        $('#editmodal').modal('show');

            //$tr = $(this).closest('tr');
            //var data = $tr.children("td").map(function() {
            //    return $(this).text();
            //}).get();
            var data = $(_this).attr('data')
            console.log(data)
            data = data.split(',')

 
            editidoperazione = data[0];
            editdataoperazione = data[1].replaceAll('/', '-');
            //editcausale = data[2];
            editdescrizione = data[2];
            editentrate = data[3];
            edituscite = data[4];
            //editentrate = editentrate.replace("€", "");
            //edituscite = edituscite.replace("€", "");
            //editentrate = editentrate.replace(/\s+/g, '');
            //edituscite = edituscite.replace(/\s+/g, '');
            edittag1 = data[5];
            editcategoria = data[6];
            //edittag3 = data[8];
            //edittag4 = data[9];

            $('#editidoperazione').val(editidoperazione);
            $('#editdataoperazione').val(editdataoperazione);
            //$('#editcausale').val(editcausale);
            $('#editdescrizione').val(editdescrizione);
            $('#editentrate').val(editentrate);
            $('#edituscite').val(edituscite);
            $('#edittag1').val(edittag1);
            $('#editcategoria').val(editcategoria);
            //$('#edittag3').val(edittag3);
            //$('#edittag4').val(edittag4);
    }
</script>