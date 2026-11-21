            <!-- Modal -->
            <div class="modal fade" id="ModalEditOperazione" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                    <div class="modal-content ba-form-modal">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modifica Operazione</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body ba-operation-form">

                                <input type="hidden" name="editidoperazione" id="editidoperazione">

                                <div class="form-group ba-form-field">
                                    <label for="editdataoperazione" class="form-label">Data Operazione</label>
                                    <input type="date" name="editdataoperazione" id="editdataoperazione"  class="form-control">
                                </div>
                                <!--<div class="form-group ba-form-field">
                                    <label>Causale</label>
                                    <input type="text" name="editcausale" id="editcausale" class="form-control" placeholder="Inserire Causale">
                                </div>-->
                                <div class="form-group ba-form-field">
                                    <label for="editdescrizione" class="form-label">Descrizione</label>
                                    <input type="text" name="editdescrizione" id="editdescrizione" class="form-control" placeholder="Inserire Descrizione">
                                </div>
                                <div class="ba-form-grid"><div class="form-group ba-form-field">
                                    <label for="editentrate" class="form-label">Entrata</label>
                                    <input type="number" step="0.01" min="0" name="editentrate" id="editentrate" class="form-control" placeholder="Inserire Entrata" value="0">
                                </div>
                                <div class="form-group ba-form-field">
                                    <label for="edituscite" class="form-label">Uscita</label>
                                    <input type="number" step="0.01" min="0" name="edituscite" id="edituscite" class="form-control" placeholder="Inserire Uscita" value="0">
                                </div></div>
                                <div class="ba-form-grid"><div class="form-group ba-form-field">
                                    <label for="edittag1" class="form-label">Tipologia</label>
                                    <select name="edittag1" id="edittag1" class="form-control form-select" required>
                                        <option value="">Seleziona...</option>
                                        <option value="Entrata">Entrata</option>
                                        <option value="Uscita">Uscita</option>
                                    </select>
                                </div>
                                <div class="form-group ba-form-field">
                                    <label for="editcategoria" class="form-label">Categoria</label>
                                    <?php
                                    echo "<select id='editcategoria' name='editcategoria' class='form-control form-select'>";
                                    echo "<option value=''></option>";
                                    foreach ($catarray as $cate) {
                                        echo "<option value='" . htmlspecialchars((string)$cate, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars((string)$cate, ENT_QUOTES, 'UTF-8') . "</option>";
                                    }
                                    echo "</select>"; ?>
                                </div></div>
                                <!--<div class="form-group ba-form-field">
                                    <label>Tag3</label>
                                    <input type="text" name="edittag3" id="edittag3" class="form-control" placeholder="Inserire Tag3">
                                </div>
                                <div class="form-group ba-form-field">
                                    <label>Tag4</label>
                                    <input type="text" name="edittag4" id="edittag4" class="form-control" placeholder="Inserire Tag4">
                                </div>-->
                            </div>
                            <div class="modal-footer ba-modal-actions">
                                <button type="button" id="closebtn" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                <button type="button" class="btn btn-primary savebtnedit">Salva modifiche</button>
                            </div>
                    </form>
                </div>
              </div>
            </div>

            <!-- Modal -->

<script>
$(function () {
    const $form = $('#ModalEditOperazione form');
    $form.on('submit', function (event) {
        event.preventDefault();
        const prefix = 'edit';
        const date = $('#'+prefix+'dataoperazione').val();
        const description = $('#'+prefix+'descrizione').val().trim();
        const tag = $('#'+prefix+'tag1').val();
        const category = $('#'+prefix+'categoria').val();
        const income = $('#'+prefix+'entrate').val();
        const expense = $('#'+prefix+'uscite').val();
        if (!/^\d{4}-\d{2}-\d{2}$/.test(date) || !description || !tag || !category ||
            income === '' || expense === '' || Number(income) < 0 || Number(expense) < 0) {
            alert('Compila data, descrizione, importi, tipologia e categoria.');
            return;
        }
        const month = Number(date.slice(5, 7));
        const months = ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
        const data = {
            ajax: 1,
            [prefix+'dataoperazione']: date,
            [prefix+'mese']: months[month-1] || '',
            [prefix+'anno']: date.slice(0,4),
            [prefix+'ordinemese']: String(month),
            [prefix+'descrizione']: description,
            [prefix+'entrate']: income,
            [prefix+'uscite']: expense,
            [prefix+'tag1']: tag,
            [prefix+'categoria']: category
        };
        data.editidoperazione = $('#editidoperazione').val();
        const $button = $form.find('.savebtnedit');
        $button.prop('disabled', true);
        $.ajax({
            type: 'POST', url: 'php/insupdoperazione.php', data: data,
            success: function () { window.location.reload(); },
            error: function (xhr) { alert(xhr.responseText || 'Errore durante il salvataggio.'); },
            complete: function () { $button.prop('disabled', false); }
        });
    });
    $form.find('.savebtnedit').on('click', function () { $form.trigger('submit'); });
});
</script>