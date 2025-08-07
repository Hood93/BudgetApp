            <!-- Modal -->
            <div class="modal fade" id="ModalNewCategoria" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserisci Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="" method="POST">
                    <div class="modal-body">

                      <div class="form-group">
                        <label> Categoria </label>
                        <input type="text" name="newcategoria" id="newcategoria" value="newcategoria" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Icona</label>
                        <input type="text" name="newiconacategoria" id="newiconacategoria" class="form-control" placeholder="Inserire HTML Icona">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="closebtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary savebtnnew">Save changes</button>
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
                  var newcategoria = $('#newcategoria').val();
                  var newiconacategoria = $('#newiconacategoria').val();
                  $.ajax({
                    type: 'POST',
                    url: 'php/insupdcategoria.php',
                    data: {
                      ajax: 1,
                      newcategoria: newcategoria,
                      newiconacategoria: newiconacategoria
                    },
                    success: function(response) {
                      console.log ("inserito");
                      document.getElementById('closebtn').click();
                      location.reload();
                    }


                  });






                });
              });
            </script>