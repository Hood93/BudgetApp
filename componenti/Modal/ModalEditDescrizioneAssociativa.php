            <!-- Modal -->
            <div class="modal fade" id="ModalEditDesAssociativa" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifica Descrizione Associativa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="POST">
                    <div class="modal-body">
                    <input type="hidden" name="editiddesassociativa" id="editiddesassociativa" value="editiddesassociativa" class="form-control">
                      <div class="form-group">
                        <label>Old Descrizione</label>
                        <input type="text" name="editolddescrizione" id="editolddescrizione" value="olddescrizione" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>New Descrizione</label>
                        <input type="text" name="editnewdescrizione" id="editnewdescrizione" class="form-control" placeholder="Inserire New Descrizione">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="closebtnnewdesass" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary savebtneditdesass">Save changes</button>
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

                $('.savebtneditdesass').on('click', function() {
                  var editiddesassociativa = $('#editiddesassociativa').val();
                  var editolddescrizione = $('#editolddescrizione').val();
                  var editnewdescrizione = $('#editnewdescrizione').val();
                  $.ajax({
                    type: 'POST',
                    url: 'php/insupddesassociativa.php',
                    data: {
                      ajax: 1,
                      editiddesassociativa:editiddesassociativa,
                      editolddescrizione: editolddescrizione,
                      editnewdescrizione: editnewdescrizione
                    },
                    success: function(response) {
                      console.log ("inserito");
                      document.getElementById('closebtnnewdesass').click();
                      gettabassdescrizione()
                    }


                  });






                });
              });
            </script>