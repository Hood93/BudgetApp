<div class="modal fade" id="ModalEditCategoria" tabindex="-1" aria-labelledby="editCategoriaTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formEditCategoria">
        <div class="modal-header">
          <h5 class="modal-title" id="editCategoriaTitle">Modifica categoria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="editidcategoria" id="editidcategoria">
          <label for="editcategoria" class="form-label">Categoria</label>
          <input type="text" name="editcategoria" id="editcategoria" class="form-control" maxlength="150" required>
          <div class="alert alert-danger mt-3 d-none" id="editCategoriaError" role="alert"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
          <button type="submit" class="btn btn-primary savebtnedit">Salva modifiche</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
$(function () {
  $('#formEditCategoria').on('submit', function (event) {
    event.preventDefault();
    const $form = $(this);
    const $button = $form.find('.savebtnedit');
    const $error = $('#editCategoriaError').addClass('d-none').text('');
    if (!$('#editcategoria').val().trim()) {
      $error.text('Inserisci il nome della categoria.').removeClass('d-none');
      return;
    }
    $button.prop('disabled', true);
    $.ajax({
      type: 'POST',
      url: 'php/insupdcategoria.php',
      data: $form.serialize(),
      success: function () { window.location.reload(); },
      error: function (xhr) {
        $error.text(xhr.responseText || 'Impossibile salvare la categoria.').removeClass('d-none');
      },
      complete: function () { $button.prop('disabled', false); }
    });
  });
});
</script>