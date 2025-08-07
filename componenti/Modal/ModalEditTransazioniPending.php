            <!-- Modal -->
            <div class="modal fade" id="ModalEditTransazioniPending" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modifica transazioni Pending</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">

                                <input type="hidden" name="editidoperazione" id="editidoperazione">

                                <div class="form-group">
                                    <label> Data Operazione </label>
                                    <input type="date" name="editdataoperazione" id="editdataoperazione" value="editdataoperazione" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Descrizione</label>
                                    <input type="text" name="editdescrizione" id="editdescrizione" class="form-control" placeholder="Inserire Descrizione">
                                </div>
                                <div class="form-group">
                                    <label>Entrata</label>
                                    <input type="number" name="editentrate" id="editentrate" class="form-control" placeholder="Inserire Entrata" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Uscita</label>
                                    <input type="number" name="edituscite" id="edituscite" class="form-control" placeholder="Inserire Uscita" value="0">
                                </div>
                                <div class="form-group">
                                    <label>Tag1</label>
                                    <select name="edittag1" id="edittag1" class="form-control" required>
                                        <option value=""></option>
                                        <option value="Entrata">Entrata</option>
                                        <option value="Uscita">Uscita</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Categoria</label>
                                    <?php
                                    echo "<select id='editcategoria' name='editcategoria' class='form-control'>";
                                    echo "<option value=''></option>";
                                    foreach ($catarray as $cate) {
                                        echo "<option value='" . $cate . "'>" . $cate . "</option>";
                                    }
                                    echo "</select>"; ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger delbtnedit">Elimina Transazione Pending</button>
                                <button type="button" id="closebtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary savebtnedit">Save changes</button>
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

                    $('.savebtnedit').on('click', function() {
                        var editidoperazione = $('#editidoperazione').val();
                        var editdataoperazione = $('#editdataoperazione').val();
                        var editdescrizione = $('#editdescrizione').val();
                        var editentrate = $('#editentrate').val();
                        var edituscite = $('#edituscite').val();
                        var edittag1 = $('#edittag1').val();
                        var editcategoria = $('#editcategoria').val();
                        var operazione = "inserimento";
                        if (edittag1 != "" && editcategoria != "") {
                            $.ajax({
                                type: 'POST',
                                url: 'php/updtransazionipending.php',
                                data: {
                                    ajax: 1,
                                    editidoperazione: editidoperazione,
                                    editdataoperazione: editdataoperazione,
                                    editmese: editmese,
                                    editanno: editanno,
                                    editordinemese: editordinemese,
                                    editdescrizione: editdescrizione,
                                    editentrate: editentrate,
                                    edituscite: edituscite,
                                    edittag1: edittag1,
                                    editcategoria: editcategoria,
                                    operazione: operazione

                                },
                                success: function(response) {
                                    document.getElementById('closebtn').click();
                                    location.reload();
                                }


                            });
                        } else {
                            alert("Tutti i campi sono obbligatori")
                        }
                    });


                    $('.delbtnedit').on('click', function() {

                        var editidoperazione = $('#editidoperazione').val();
                        var operazione = "elimina"
                        var conferma = confirm("Sei sicuro di voler eliminare questa transazione");
                        if (conferma == true) {
                            $.ajax({
                                type: 'POST',
                                url: 'php/updtransazionipending.php',
                                data: {
                                    ajax: 1,
                                    editidoperazione: editidoperazione,
                                    operazione: operazione

                                },
                                success: function(response) {
                                    document.getElementById('closebtn').click();
                                    location.reload();
                                }


                            });
                        } else {
                            alert("Transazione non eliminata")
                        }
                    })


                });
            </script>