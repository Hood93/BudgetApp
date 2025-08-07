            <!-- Modal -->
            <div class="modal fade" id="ModalInsertCreditore" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserisci Creditore</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="POST">
                    <div class="modal-body">

                      <div class="form-group">
                        <label> Nome </label>
                        <input type="text" name="newnomecreditore" id="newnomecreditore" class="form-control">
                      </div>
                      <div class="form-group">
                        <label> Descrizione </label>
                        <input type="text" name="newdescrizionecreditore" id="newdescrizionecreditore" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Durata</label>
                        <input type="number" name="newduratacreditore" id="newduratacreditore" class="form-control" placeholder="Inserire HTML Icona">
                      </div>
                      <div class="form-group">
                        <label>Importo</label>
                        <input type="number" name="newimportocreditore" id="newimportocreditore" class="form-control" placeholder="Inserire HTML Icona">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="closebtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary savebtnnewcreditore">Save changes</button>
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

                $('.savebtnnewcreditore').on('click', function() {
                  var newnomecreditore = $('#newnomecreditore').val();
                  var newduratacreditore = $('#newduratacreditore').val();
                  var newimportocreditore = $('#newimportocreditore').val();
                  var newdescrizionecreditore = $('#newdescrizionecreditore').val();
                  
                  //inserire check numeri 
                  $.ajax({
                    type: 'POST',
                    url: 'php/inscreditore.php',
                    data: {
                      ajax: 1,
                      newnomecreditore: newnomecreditore,
                      newduratacreditore: newduratacreditore,
                      newimportocreditore: newimportocreditore,
                      newdescrizionecreditore: newdescrizionecreditore
                    },
                    success: function(response) {
                      document.getElementById('closebtn').click();
                      location.reload();
                    }


                  });






                });
              });
            </script>