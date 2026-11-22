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
                        <input type="number" name="newdurataopric" id="newdurataopric" min="1" max="12" class="form-control" onchange="DettaglioDurata()" placeholder="Inserire Durata">
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
              (function () {
                const months = ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
                const $date = $('#newdataoperazioneopric');
                const $month = $('#newmesepartopric');
                const $duration = $('#newdurataopric');
                const $confirm = $('#swtichconferma');
                const $save = $('#idsavebtnnewopric');
                let saving = false;

                function preview() {
                    const match = /^(\\d{4})-(\\d{2})-(\\d{2})$/.exec($date.val() || '');
                    const duration = Number($duration.val());
                    const monthIndex = months.indexOf($month.val());
                    if (!match || monthIndex < 0 || !Number.isInteger(duration) || duration < 1 || duration > 12) {
                        $('#messaggiodurata').text('Seleziona una data, il mese di partenza e una durata da 1 a 12 mesi.');
                        return false;
                    }
                    const year = Number(match[1]);
                    const endIndex = monthIndex + duration - 1;
                    const endMonth = endIndex % 12;
                    const endYear = year + Math.floor(endIndex / 12);
                    $('#messaggiodurata').text('Verrà inserita una transazione ricorrente da ' +
                        months[monthIndex] + ' del ' + year + ' fino a ' + months[endMonth] + ' del ' + endYear +
                        ' (' + duration + ' operazioni mensili).');
                    return true;
                }
                function enable() {
                    $save.prop('disabled',saving || !$confirm.is(':checked') || !preview());
                }
                window.DettaglioDurata = preview;
                window.EnableInsert = enable;
                $date.on('change input',function () {
                    const match = /^\\d{4}-(\\d{2})-\\d{2}$/.exec($date.val() || '');
                    if (match && Number(match[1]) >= 1) $month.val(months[Number(match[1])-1] || '');
                    enable();
                });
                $month.on('change',enable);
                $duration.on('input change',enable);
                $confirm.on('change',enable);
                $save.on('click',function () {
                    if (saving || !preview() || !$confirm.is(':checked')) return;
                    const data = {
                        newdataoperazioneopric:$date.val(),
                        newmesepartopric:$month.val(),
                        newdescrizioneopric:$('#newdescrizioneopric').val(),
                        newcategoriaopric:$('#newcategoriaopric').val(),
                        newentrateopric:$('#newentrateopric').val(),
                        newusciteopric:$('#newusciteopric').val(),
                        newtag1opric:$('#newtag1opric').val(),
                        newdurataopric:$duration.val()
                    };
                    saving=true;enable();
                    $.ajax({type:'POST',url:'php/insoperazioneric.php',data:data,dataType:'json'})
                        .done(function (response) {
                            if (response && response.ok) {
                                $('#ModalNewOperazioneRic').modal('hide');
                                location.reload();
                            } else {
                                alert((response && response.error) || 'Impossibile salvare la ricorrenza');
                            }
                        }).fail(function (xhr) {
                            alert((xhr.responseJSON && xhr.responseJSON.error) || 'Errore durante il salvataggio della ricorrenza');
                        }).always(function () {saving=false;enable();});
                });
                enable();
              })();
            </script>
