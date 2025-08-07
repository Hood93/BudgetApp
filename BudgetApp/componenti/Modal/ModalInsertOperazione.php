            <!-- Modal -->
            <div class="modal fade" id="ModalNewOperazione" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserisci Operazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="POST">
                    <div class="modal-body">

                      <div class="form-group">
                        <label> Data Operazione </label>
                        <input type="date" name="newdataoperazione" id="newdataoperazione" value="newdataoperazione" class="form-control">
                      </div>
                      <!--<div class="form-group">
                        <label>Causale</label>
                        <input type="text" name="newcausale" id="newcausale" class="form-control" placeholder="Inserire Causale">
                      </div>-->
                      <div class="form-group">
                        <label>Descrizione</label>
                        <input type="text" name="newdescrizione" id="newdescrizione" class="form-control" placeholder="Inserire Descrizione">
                      </div>
                      <div class="form-group">
                        <label>Entrata</label>
                        <input type="number" name="newentrate" id="newentrate" class="form-control" placeholder="Inserire Entrata" value = "0">
                      </div>
                      <div class="form-group">
                        <label>Uscita</label>
                        <input type="number" name="newuscite" id="newuscite" class="form-control" placeholder="Inserire Uscita" value = "0">
                      </div>
                      <div class="form-group">
                        <label>Tag1</label>
                        <select name="newtag1" id="newtag1" class="form-control" required>
                          <option value=""></option>
                          <option value="Entrata">Entrata</option>
                          <option value="Uscita">Uscita</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Categoria</label>
                        <?php
                        echo "<select id='newcategoria' name='newcategoria' class='form-control'>";
                        echo "<option value=''></option>";
                        foreach ($catarray as $cate) {
                          echo "<option value='" . $cate . "'>" . $cate . "</option>";
                        }
                        echo "</select>"; ?>
                      </div>
                      <!--<div class="form-group">
                        <label>Tag3</label>
                        <input type="text" name="newtag3" id="newtag3" class="form-control" placeholder="Inserire Tag3">
                      </div>
                      <div class="form-group">
                        <label>Tag4</label>
                        <input type="text" name="newtag4" id="newtag4" class="form-control" placeholder="Inserire Tag4">
                      </div>-->
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="closebtn" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                      <button type="button" class="btn btn-dark savebtnnew">Salva</button>
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

                $('.savebtnnew').on('click', function() {
                  var newdataoperazione = $('#newdataoperazione').val();
                  var newcausale = $('#newcausale').val();
                  var newdescrizione = $('#newdescrizione').val();
                  var newentrate = $('#newentrate').val();
                  var newuscite = $('#newuscite').val();
                  var newtag1 = $('#newtag1').val();
                  var newcategoria = $('#newcategoria').val();
                  var newtag3 = $('#newtag3').val();
                  var newtag4 = $('#newtag4').val();
                  var splitdata = newdataoperazione.split('-');
                  switch (splitdata[1]) {
                    case "01":
                      var newmese = "Gennaio";
                      var newordinemese = "1";
                      break;
                    case "02":
                      var newmese = "Febbraio";
                      var newordinemese = "2";
                      break;
                    case "03":
                      var newmese = "Marzo";
                      var newordinemese = "3";
                      break;
                    case "04":
                      var newmese = "Aprile";
                      var newordinemese = "4";
                      break;
                    case "05":
                      var newmese = "Maggio";
                      var newordinemese = "5";
                      break;
                    case "06":
                      var newmese = "Giugno";
                      var newordinemese = "6";
                      break;
                    case "07":
                      var newmese = "Luglio";
                      var newordinemese = "7";
                      break;
                    case "08":
                      var newmese = "Agosto";
                      var newordinemese = "8";
                      break;
                    case "09":
                      var newmese = "Settembre";
                      var newordinemese = "9";
                      break;
                    case "10":
                      var newmese = "Ottobre";
                      var newordinemese = "10";
                      break;
                    case "11":
                      var newmese = "Novembre";
                      var newordinemese = "11";
                      break;
                    case "12":
                      var newmese = "Dicembre";
                      var newordinemese = "12";
                      break;
                    default:
                      var newmese = "";
                  }
                  var newanno = splitdata[0];
                  if (newtag1 != "") {
                    $.ajax({
                    type: 'POST',
                    url: 'php/insupdoperazione.php',
                    data: {
                      ajax: 1,
                      newdataoperazione: newdataoperazione,
                      newmese: newmese,
                      newanno: newanno,
                      newordinemese: newordinemese,
                      newcausale: newcausale,
                      newdescrizione: newdescrizione,
                      newentrate: newentrate,
                      newuscite: newuscite,
                      newtag1: newtag1,
                      newcategoria: newcategoria,
                      newtag3: newtag3,
                      newtag4: newtag4
                    },
                    success: function(response) {
                      document.getElementById('closebtn').click();
                      location.reload();
                    }
                  });
                  }else{
                    alert("Tutti i campi sono obbligatori")
                  }
                });
              });
            </script>