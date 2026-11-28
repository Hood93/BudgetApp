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
echo "<section class='ba-recurring-section'><div class='ba-report-heading'><div><h3>Spese ricorrenti</h3><p>Verde: attive · Grigio: scadute</p></div></div><div class='table-responsive ba-recurring-scroll'>";
echo "<table class='table table-hover align-middle'>
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
      echo "<tr class='ba-recurring-active'>";
      break;
    case "Disattiva":
      echo "<tr class='ba-recurring-expired'>";
      break;
  }
  echo "<td style='display:none;'>" . $row["idtransazioniricorrenti"] . "</td>";
  echo "<td data-label='Descrizione' class='text-start'>" . htmlspecialchars((string)$row['descrizione'], ENT_QUOTES, 'UTF-8') . "</td>";
  echo "<td data-label='Mese iniziale' class='text-start'>" . $mesi[$row['meseiniziale']] . "</td>";
  echo "<td data-label='Anno iniziale' class='text-start'>" . $row['annoiniziale'] . "</td>";
  echo "<td data-label='Mese finale' class='text-start'>" . $mesi[$row['mesefinale']] . "</td>";
  echo "<td data-label='Anno finale' class='text-start'>" . $row['annofinale'] . "</td>";
  echo "<td data-label='Categoria' class='text-start'>" . htmlspecialchars((string)$row['categoria'], ENT_QUOTES, 'UTF-8') . "</td>";
  echo "<td data-label='Entrata' class='text-start'>" . number_format((float)$row['entrata'], 2, ',', '.') . " €</td>";
  echo "<td data-label='Uscita' class='text-start'>" . number_format((float)$row['uscita'], 2, ',', '.') . " €</td>";
  switch($row['stato']){
    case "Attiva":
      echo "<td data-label='Azioni' class='text-start'><button type='button' class='btn text-start viewbtn'><i class='fa-solid fa-circle-info fa-lg pe-2' style='color: #000000;'></i></button><button type='button' class='btn text-start delbtn'><i class='fa-sharp fa-solid fa-trash fa-lg ps-2' style='color: #000000;'></i></button></td>";
      break;
    case "Disattiva":
      echo "<td data-label='Azioni' class='text-start'><button type='button' class='btn text-start viewbtn'><i class='fa-solid fa-circle-info fa-lg pe-2' style='color: #000000;'></i></button></td>";
      break;
  }
  echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div></section>";
?>
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

            
            if (risp === true){
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