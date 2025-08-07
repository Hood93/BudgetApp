<?php
//ini_set('display_errors', 1);
include "db.php";
include "utility.php";

$query = "SELECT * from transazioniricorrenti";


//Ciclo il risultato
$result = mysqli_query($conn, $query);

if (!$result) {
  echo "An error occurred.\n";
  exit;
}
echo "<div class='table-responsive'>";
echo "<table class='table table-responsive table-bordered border-black'>
<thead>
<tr>";
echo "<th scope='col'>Descrizione</th>";
echo "<th scope='col'>Mese Iniziale</th>";
echo "<th scope='col'>Anno Iniziale</th>";
echo "<th scope='col'>Mese Finale</th>";
echo "<th scope='col'>Anno Finale</th>";
echo "<th scope='col'>Categoria</th>";
echo "<th scope='col'>Entrata</th>";
echo "<th scope='col'>Uscita</th>";
echo "<th scope='col'>Dettaglio</th>";

echo "</tr>";
echo "</thead>";
echo "<tbody>";
while ($row = mysqli_fetch_assoc($result)) {
  switch($row['stato']){
    case "Attiva":
      echo "<tr class='tractive'>";
      break;
    case "Disattiva":
      echo "<tr class='trdisable'>";
      break;
  }
  echo "<td style='display:none;'>" . $row["idtransazioniricorrenti"] . "</td>";
  echo "<td class='text-start'>" . $row['descrizione'] . "</td>";
  echo "<td class='text-start'>" . $mesi[$row['meseiniziale']] . "</td>";
  echo "<td class='text-start'>" . $row['annoiniziale'] . "</td>";
  echo "<td class='text-start'>" . $mesi[$row['mesefinale']] . "</td>";
  echo "<td class='text-start'>" . $row['annofinale'] . "</td>";
  echo "<td class='text-start'>" . $row['categoria'] . "</td>";
  echo "<td class='text-start'>" . $row['entrata'] . "</td>";
  echo "<td class='text-start'>" . $row['uscita'] . "</td>";
  switch($row['stato']){
    case "Attiva":
      echo "<td class='text-start'><button type='button' class='btn text-start viewbtn'><i class='fa-solid fa-circle-info fa-lg pe-2' style='color: #000000;'></i></button><button type='button' class='btn text-start delbtn'><i class='fa-sharp fa-solid fa-trash fa-lg ps-2' style='color: #000000;'></i></button></td>";
      break;
    case "Disattiva":
      echo "<td class='text-start'><button type='button' class='btn text-start viewbtn'><i class='fa-solid fa-circle-info fa-lg pe-2' style='color: #000000;'></i></button></td>";
      break;
  }
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
        $('.viewbtn').click(function() {
            $('#VistaTransazioniRicorrenti').modal('show');
            
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            console.log(data);
            viewidtransricorrenti = data[0];
            viewdesctransricorrenti = data[1];

            $('#viewidtransricorrenti').val(viewidtransricorrenti);
            $('#viewdesctransricorrenti').text(viewdesctransricorrenti);

            $.ajax({
                    type: 'POST',
                    url: 'php/preleva_dati_dettagliotransricorrenti.php',
                    data: {
                      ajax: 1,
                      viewidtransricorrenti: viewidtransricorrenti
                    },
                    success: function(response) {
                      $("#dettagliotransazioniricorrenti").html(response);
                    }
                  });

        });

        $('.delbtn').click(function(){
          $tr = $(this).closest('tr');
          var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            delidtransricorrenti = data[0];

            var risp = confirm("Verranno eliminate le transazioni ricorrenti successive e uguali alla data di oggi")

            
            if (risp = true){
            $.ajax({
                    type: 'POST',
                    url: 'php/updoperazioneric.php',
                    data: {
                      ajax: 1,
                      delidtransricorrenti: delidtransricorrenti
                    },
                    success: function(response) {
                      $.ajax({
                    type: 'POST',
                    url: 'php/preleva_dati_transricorrenti.php',
                    data: {
                      ajax: 1,
                    },
                    success: function(response) {
                      $("#restransazioniricorrenti").html(response);
                    }
                  });
                    }
                  });
                }
            

        })
    });
</script>