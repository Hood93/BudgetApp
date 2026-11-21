<div class="modal fade" id="ModalNewCategoria" tabindex="-1" aria-labelledby="newCategoriaTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formNewCategoria">
        <div class="modal-header">
          <h5 class="modal-title" id="newCategoriaTitle">Inserisci categoria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
          <label for="newcategoria" class="form-label">Categoria</label>
          <input type="text" name="newcategoria" id="newcategoria" class="form-control" maxlength="150" required>
          <label for="newiconacategoria" class="form-label mt-3">Icona Font Awesome</label>
          <input type="text" name="newiconacategoria" id="newiconacategoria" class="form-control" placeholder='Es. &lt;i class="fa-solid fa-house"&gt;&lt;/i&gt;'>
          <div class="alert alert-danger mt-3 d-none" id="newCategoriaError" role="alert"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
          <button type="submit" class="btn btn-primary savebtnnew">Salva categoria</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
$(function () {
  $('#formNewCategoria').on('submit', function (event) {
    event.preventDefault();
    const $form = $(this);
    const $button = $form.find('.savebtnnew');
    const $error = $('#newCategoriaError').addClass('d-none').text('');
    if (!$('#newcategoria').val().trim()) {
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
        $error.text(xhr.responseText || 'Impossibile creare la categoria.').removeClass('d-none');
      },
      complete: function () { $button.prop('disabled', false); }
    });
  });
});
</script>