            <!-- Modal -->
            <div class="modal fade" id="ModalEditOperazione" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modifica Operazione</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">

                                <input type="hidden" name="editidoperazione" id="editidoperazione">

                                <div class="form-group">
                                    <label> Data Operazione </label>
                                    <input type="date" name="editdataoperazione" id="editdataoperazione" value="editdataoperazione" class="form-control">
                                </div>
                                <!--<div class="form-group">
                                    <label>Causale</label>
                                    <input type="text" name="editcausale" id="editcausale" class="form-control" placeholder="Inserire Causale">
                                </div>-->
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
                                <!--<div class="form-group">
                                    <label>Tag3</label>
                                    <input type="text" name="edittag3" id="edittag3" class="form-control" placeholder="Inserire Tag3">
                                </div>
                                <div class="form-group">
                                    <label>Tag4</label>
                                    <input type="text" name="edittag4" id="edittag4" class="form-control" placeholder="Inserire Tag4">
                                </div>-->
                            </div>
                            <div class="modal-footer">
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
                        //var editcausale = $('#editcausale').val();
                        var editdescrizione = $('#editdescrizione').val();
                        var editentrate = $('#editentrate').val();
                        var edituscite = $('#edituscite').val();
                        var edittag1 = $('#edittag1').val();
                        var editcategoria = $('#editcategoria').val();
                        //var edittag3 = $('#edittag3').val();
                        //var edittag4 = $('#edittag4').val();
                        var splitdata = editdataoperazione.split('-');
                        //editentrate = editentrate.replace("€", "");
                        //edituscite = edituscite.replace("€", "");
                        //editentrate = editentrate.replace(/\s+/g, '');
                        //edituscite = edituscite.replace(/\s+/g, '');
                        switch (splitdata[1]) {
                            case "01":
                                var editmese = "Gennaio";
                                var editordinemese = "1";
                                break;
                            case "02":
                                var editmese = "Febbraio";
                                var editordinemese = "2";
                                break;
                            case "03":
                                var editmese = "Marzo";
                                var editordinemese = "3";
                                break;
                            case "04":
                                var editmese = "Aprile";
                                var editordinemese = "4";
                                break;
                            case "05":
                                var editmese = "Maggio";
                                var editordinemese = "5";
                                break;
                            case "06":
                                var editmese = "Giugno";
                                var editordinemese = "6";
                                break;
                            case "07":
                                var editmese = "Luglio";
                                var editordinemese = "7";
                                break;
                            case "08":
                                var editmese = "Agosto";
                                var editordinemese = "8";
                                break;
                            case "09":
                                var editmese = "Settembre";
                                var editordinemese = "9";
                                break;
                            case "10":
                                var editmese = "Ottobre";
                                var editordinemese = "10";
                                break;
                            case "11":
                                var editmese = "Novembre";
                                var editordinemese = "11";
                                break;
                            case "12":
                                var editmese = "Dicembre";
                                var editordinemese = "12";
                                break;
                            default:
                                var editmese = "";
                        }
                        var editanno = splitdata[0];
                        if (edittag1 != "") {
                        $.ajax({
                            type: 'POST',
                            url: 'php/insupdoperazione.php',
                            data: {
                                ajax: 1,
                                editidoperazione: editidoperazione,
                                editdataoperazione: editdataoperazione,
                                editmese: editmese,
                                editanno: editanno,
                                editordinemese: editordinemese,
                                //editcausale: editcausale,
                                editdescrizione: editdescrizione,
                                editentrate: editentrate,
                                edituscite: edituscite,
                                edittag1: edittag1,
                                editcategoria: editcategoria,
                                //edittag3: edittag3,
                                //edittag4: edittag4
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
                });
            </script>