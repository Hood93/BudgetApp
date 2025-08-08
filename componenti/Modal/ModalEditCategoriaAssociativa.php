            <!-- Modal -->
            <div class="modal fade" id="ModalEditCatAssociativa" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifica Categoria Associativa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="POST">
                    <div class="modal-body">
                    <input type="hidden" name="editidcatassociativa" id="editidcatassociativa" value="editidcatassociativa" class="form-control">
                      <div class="form-group">
                        <label>Descrizione</label>
                        <input type="text" name="editdescrizione" id="editdescrizione"  class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Categoria Associata</label>
                        <input type="text" name="editcatassociativa" id="editcatassociativa" class="form-control">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="closebtneditcatass" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary savebtneditcatass">Save changes</button>
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

                $('.savebtneditcatass').on('click', function() {
                  var editidcatassociativa = $('#editidcatassociativa').val();
                  var editdescrizione = $('#editdescrizione').val();
                  var editcatassociativa = $('#editcatassociativa').val();
                  $.ajax({
                    type: 'POST',
                    url: 'php/insupdcatassociativa.php',
                    data: {
                      ajax: 1,
                      editidcatassociativa:editidcatassociativa,
                      editdescrizione: editdescrizione,
                      editcatassociativa: editcatassociativa
                    },
                    success: function(response) {
                      console.log ("inserito");
                      document.getElementById('closebtneditcatass').click();
                    }


                  });






                });
              });
            </script>