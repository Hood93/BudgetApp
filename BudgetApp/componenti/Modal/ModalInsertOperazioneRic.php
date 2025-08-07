            <!-- Modal -->
            <div class="modal fade" id="ModalNewOperazioneRic" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="ModalNewOperazioneRicLabel">Inserisci Operazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="POST">
                    <div class="modal-body">
                    <div class="form-group">
                        <label> Data Operazione </label>
                        <input type="date" name="newdataoperazioneopric" id="newdataoperazioneopric" value="newdataoperazioneopric" class="form-control">
                        <p class="small">Verrà preso in considerazione solo il giorno</p>
                      </div>
                      <div class="form-group">
                        <label> Descrizione </label>
                        <input type="text" name="newdescrizioneopric" id="newdescrizioneopric" placeholder="Inserire Descrizione" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Categoria</label>
                        <?php
                        //include "../../php/utility.php";
                        echo "<select id='newcategoriaopric' name='newcategoriaopric' class='form-control'>";
                        echo "<option value=''></option>";
                        foreach ($catarray as $cate) {
                          echo "<option value='" . $cate . "'>" . $cate . "</option>";
                        }
                        echo "</select>"; ?>
                      </div>
                      <div class="form-group">
                        <label>Mese di Partenza</label>
                        <?php
                        echo "<select id='newmesepartopric' name='newmesepartopric' class='form-control'>";
                        echo "<option value=''></option>";
                        foreach ($mesi as $mesi) {
                          echo "<option value='" . $mesi . "'>" . $mesi . "</option>";
                        }
                        echo "</select>"; ?>
                      </div>
                      <div class="form-group">
                        <label>Entrate</label>
                        <input type="text" name="newentrateopric" id="newentrateopric" class="form-control" placeholder="Inserire Entrata">
                      </div>
                      <div class="form-group">
                        <label>Uscite</label>
                        <input type="text" name="newusciteopric" id="newusciteopric" class="form-control" placeholder="Inserire Uscita">
                      </div>
                      <div class="form-group">
                        <label>Tag1</label>
                        <input type="text" name="newtag1opric" id="newtag1opric" class="form-control" placeholder="Inserire Tag1">
                      </div>
                      <div class="form-group">
                        <label>Durata</label>
                        <input type="number" name="newdurataopric" id="newdurataopric" max="12" class="form-control" onchange="DettaglioDurata()" placeholder="Inserire Durata">
                        <p id="messaggiodurata"></p>
                      </div>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" onchange="EnableInsert()" role="switch" id="swtichconferma">
                        <label class="form-check-label" for="swtichconferma">Conferma</label>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id= "closebtnnewopric" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                      <button type="button" id= "idsavebtnnewopric" class="btn btn-dark savebtnnewopric" disabled="true">Salva</button>
                    </div>
                </div>
                </form>
              </div>
            </div>

            <!-- Modal -->

            <!-- Jquery CDN -->
            <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
            <!-- End of Jquery CDN -->
            <script>
              $(document).ready(function() {
                $('.savebtnnewopric').on('click', function() {
                  var newdataoperazioneopric = $('#newdataoperazioneopric').val();
                  var splitdata = newdataoperazioneopric.split('-');
                  var newdescrizioneopric = $('#newdescrizioneopric').val();
                  var newcategoriaopric = $('#newcategoriaopric').val();
                  var newmesepartopric = $('#newmesepartopric').val();
                  var newentrateopric = $('#newentrateopric').val();
                  var newusciteopric = $('#newusciteopric').val();
                  var newtag1opric = $('#newtag1opric').val();
                  var newdurataopric = $('#newdurataopric').val();
                  var newannoopric = new Date();
                  newannoopric = newannoopric.getFullYear();
                  switch (newmesepartopric) {
                    case "Gennaio":
                      var newnummesepartopric = "01";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "1";
                      break;
                    case "Febbraio":
                      var newnummesepartopric = "02";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "2";
                      break;
                    case "Marzo":
                      var newnummesepartopric = "03";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "3";
                      break;
                    case "Aprile":
                      var newnummesepartopric = "04";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "4";
                      break;
                    case "Maggio":
                      var newnummesepartopric = "05";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "5";
                      break;
                    case "Giugno":
                      var newnummesepartopric = "06";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "6";
                      break;
                    case "Luglio":
                      var newnummesepartopric = "07";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "7";
                      break;
                    case "Agosto":
                      var newnummesepartopric = "08";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "8";
                      break;
                    case "Settembre":
                      var newnummesepartopric = "09";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "9";
                      break;
                    case "Ottobre":
                      var newnummesepartopric = "10";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "10";
                      break;
                    case "Novembre":
                      var newnummesepartopric = "11";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "11";
                      break;
                    case "Dicembre":
                      var newnummesepartopric = "12";
                      var newnumgiornopartopric = splitdata[2];
                      var newordinemeseopric = "12";
                      break;
                    default:
                      var newdataopric = "";
                  }

                  $.ajax({
                    type: 'POST',
                    url: 'php/insoperazioneric.php',
                    data: {
                      ajax: 1,
                      newmesepartopric: newmesepartopric,
                      newannoopric: newannoopric,
                      newordinemeseopric: newordinemeseopric,
                      newdescrizioneopric: newdescrizioneopric,
                      newusciteopric: newusciteopric,
                      newtag1opric: newtag1opric,
                      newentrateopric: newentrateopric,
                      newcategoriaopric: newcategoriaopric,
                      newnumgiornopartopric: newnumgiornopartopric,
                      newnummesepartopric: newnummesepartopric,
                      newdurataopric: newdurataopric
                    },
                    success: function(response) {
                      document.getElementById('closebtnnewopric').click();
                      location.reload();
                    }

                  });
                });
              });

              function DettaglioDurata() {
                var annoini = new Date();
                annoini = annoini.getFullYear(); // Prendo anno attuale
                var meseinizio = $("#newmesepartopric :selected").text(); //Prendo il mese iniziale text
                var durata = $("#newdurataopric").val(); //Prendo la durata
                const mesenum = {
                  "Gennaio": 1,
                  "Febbraio": 2,
                  "Marzo": 3,
                  "Aprile": 4,
                  "Maggio": 5,
                  "Giugno": 6,
                  "Luglio": 7,
                  "Agosto": 8,
                  "Settembre": 9,
                  "Ottobre": 10,
                  "Novembre": 11,
                  "Dicembre": 12
                };
                var meseini = mesenum[meseinizio] //Mese iniziale in numero

                var mesefin = meseini
                var annofin = annoini;

                for (i = 1; i < durata; i++) {
                  console.log("for")
                  if (mesefin >= 12) {
                    mesefin = 0
                    annofin = annoini + 1;
                  } else {
                    annofin = annoini
                  }
                  mesefin = mesefin + 1;
                }
                
                const mesenumfin = {
                  "1": "Gennaio",
                  "2": "Febbraio",
                  "3": "Marzo",
                  "4": "Aprile",
                  "5": "Maggio",
                  "6": "Giugno",
                  "7": "Luglio",
                  "8": "Agosto",
                  "9": "Settembre",
                  "10": "Ottobre",
                  "11": "Novembre",
                  "12": "Dicembre"
                };
                $('#messaggiodurata').html('Verrà inserita una transazione ricorrente da ' + meseinizio + " del " + annoini + " fino al " + mesenumfin[mesefin] + " del " + annofin)
              }

              function EnableInsert(){
                var conferma = $('#swtichconferma').is(":checked");
                var btnconferma = document.getElementById("idsavebtnnewopric")
                if (conferma != true) {
                  $("#idsavebtnnewopric").attr('disabled','disabled');  
                } else  {
                  $("#idsavebtnnewopric").prop("disabled", false);
                } 
              }
            </script>