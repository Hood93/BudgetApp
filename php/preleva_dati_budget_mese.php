<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="table_TransazioniTotali">
        <thead class="bg-light text-dark">
            <tr>
                <th scope="col">Data Operazione</th>
                <!--<th scope="col">Causale</th>-->
                <th scope="col">Descrizione</th>
                <th scope="col">Entrate</th>
                <th scope="col">Uscite</th>
                <th scope="col">Tag1</th>
                <th scope="col">Categorie</th>
                <!--<th scope="col">Tag3</th>
                <th scope="col">Tag4</th>
                <th scope="col">Azioni</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            //ini_set('display_errors', 1);
            include "db.php";
            $anno = $_POST['anno'];
            $mese = $_POST['mese'];
            $mese = $mese + 1 ;

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

       



            //$query = "SELECT * FROM budgettable ";
            $query = "select  * from budget.budgettable left join budget.categoriebudget on budget.budgettable.categorie = budget.categoriebudget.categoria WHERE mese = '".$mesetext."' AND anno = '".$anno."'";




            //if (isset($_POST["cerca"])) {
            //$query .=
            //        //WHERE idbackup_sazka LIKE "%'.$_POST["search"]["value"].'%" 
            //        "       OR causale LIKE '%" . $_POST["cerca"] . "%' 
            //                OR descrizione LIKE '%" . $_POST["cerca"] . "%' 
            //                OR entrate LIKE '%" . $_POST["cerca"] . "%' 
            //                OR uscite LIKE '%" . $_POST["cerca"] . "%'
            //                OR tag1 LIKE '%" . $_POST["cerca"] . "%'
            //                OR categorie LIKE '%" . $_POST["cerca"] . "%'
            //                OR tag3 LIKE '%" . $_POST["cerca"] . "%'
            //                OR tag4 LIKE '%" . $_POST["cerca"] . "%'";
            //}
            //if (isset($_POST["anno"])) {
            //$query .=
            //" WHERE anno LIKE '%" . $_POST["anno"] . "%'
            //AND mese LIKE '%" . $mesetext . "%'
            //        AND categoria LIKE '%" . $_POST["categoria"] . "%'";
           // } else if (isset($_POST["mese"])) {
            //    $query .=
           //     " WHERE mese LIKE '%" . $mesetext . "%'
           //     AND anno LIKE '%" . $_POST["anno"] . "%'
           //     AND categoria LIKE '%" . $_POST["categoria"] . "%'";
            //} elseif (isset($_POST["categoria"])) {
            //    " WHERE categoria LIKE '%" . $_POST["categoria"] . "%'
            //    AND anno LIKE '%" . $_POST["anno"] . "%'
            //    AND mese LIKE '%" . $mesetext . "%'";
            //}

            $query .= " order by data_operazione desc";


            
            //Ciclo il risultato
            $result = mysqli_query($conn, $query);
            if (!$result) {
                echo "An error occurred.\n";
                exit;
            }

            while ($row = mysqli_fetch_assoc($result)) {

                if ($row['causale'] == "Iniziale"){

                } else {

                echo "<tr>";
                echo "<td style='display:none;'>" . $row["idBudgetTable"] . "</td>";
                echo "<td>" . $row["data_operazione"] . "</td>";
                //echo "<td>" . $row["causale"] . "</td>";
                echo "<td>" . $row["descrizione"] . "</td>";
                echo "<td> € " . $row["entrate"] . "</td>";
                echo "<td> € " . $row["uscite"] . "</td>";
                echo "<td>" . $row["tag1"] . "</td>";
                echo "<td>" . $row["icona"] . " " . $row["categorie"] . "</td>";
                //echo "<td>" . $row["tag3"] . "</td>";
                //echo "<td>" . $row["tag4"] . "</td>";
                //echo "<td>" . "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalEditOperazione' class='btn btn-success editbtn'><span><i class='fa-solid fa-pen-to-square'></i></span></button>" . "</td>";
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

            $('#editmodal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            console.log(data);
            editidoperazione = data[0];
            editdataoperazione = data[1].replaceAll('/', '-');
            editcausale = data[2];
            editdescrizione = data[3];
            editentrate = data[4];
            edituscite = data[5];
            editentrate = editentrate.replace("€","");
            edituscite = edituscite.replace("€","");
            editentrate = editentrate.replace(/\s+/g,'');
            edituscite = edituscite.replace(/\s+/g,'');
            edittag1 = data[6];
            editcategoria = data[7].replaceAll(" ","");
            edittag3 = data[8];
            edittag4 = data[9];

            $('#editidoperazione').val(editidoperazione);
            $('#editdataoperazione').val(editdataoperazione);
            $('#editcausale').val(editcausale);
            $('#editdescrizione').val(editdescrizione);
            $('#editentrate').val(editentrate);
            $('#edituscite').val(edituscite);
            $('#edittag1').val(edittag1);
            $('#editcategoria').val(editcategoria);
            $('#edittag3').val(edittag3);
            $('#edittag4').val(edittag4);
        });
    });
</script>