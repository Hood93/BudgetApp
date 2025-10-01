            <!-- Modal -->
            <div class="modal fade" id="ModalNewCatAssociativa" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserisci Categoria Associativa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="POST">
                    <div class="modal-body">
                      <div class="form-group">
                        <label>Descrizione</label>
                        <input type="text" name="newdescrizioneass" id="newdescrizioneass"  class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Categoria Associata</label>
                        <input type="text" name="newcategoriaassociata" id="newcategoriaassociata" class="form-control" placeholder="Inserire New Descrizione">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="closebtninsnewcatass" class="btn btn-secondary closebtninsnewcatass" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary savebtnnewcatass">Save changes</button>
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

                $('.savebtnnewcatass').on('click', function() {
                  var newdescrizioneass = $('#newdescrizioneass').val();
                  var newcategoriaassociata = $('#newcategoriaassociata').val();
                  $.ajax({
                    type: 'POST',
                    url: 'php/insupdcatassociativa.php',
                    data: {
                      ajax: 1,
                      newdescrizioneass: newdescrizioneass,
                      newcategoriaassociata: newcategoriaassociata
                    },
                    success: function(response) {
                      console.log ("inserito");
                      document.getElementById('closebtninsnewcatass').click();
                    }


                  });






                });
              });
            </script>