            <!-- Modal -->
            <div class="modal fade" id="ModalEditCategoria" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modifica Operazione</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">

                                <input type="hidden" name="editidcategoria" id="editidcategoria">

                                <div class="form-group">
                                    <label> Categoria </label>
                                    <input type="text" name="editcategoria" id="editcategoria" value="editcategoria" class="form-control">
                                </div>
                               <!-- <div class="form-group">
                                    <label>Icona</label>
                                    <input type="text" name="editiconacategoria" id="editiconacategoria" class="form-control" placeholder="Inserire Causale">
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
                        var editidcategoria = $('#editidcategoria').val();
                        var editcategoria = $('#editcategoria').val();
                        $.ajax({
                            type: 'POST',
                            url: 'php/insupdcategoria.php',
                            data: {
                                ajax: 1,
                                editidcategoria: editidcategoria,
                                editcategoria: editcategoria,
                            },
                            success: function(response) {
                                document.getElementById('closebtn').click();
                                location.reload();
                            }


                        });


                    });
                });
            </script>