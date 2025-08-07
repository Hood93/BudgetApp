<!-- Modal Dettaglio Categorie Mese -->

<div class="modal modal-lg" id="VistaDettaglioCategorieMese" role="dialog" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Elenco dettaglio per categoria &nbsp</h5>
        <h5 class="modal-title" id="viewcategoriadettaglio" name="viewcategoriadettaglio"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="dettagliocategoriemese"></div>
        <button type="button" id="insertamex" style="visibility: hidden;" class="btn insertamex mb-3 "><i class="fa-solid fa-circle-plus fa-lg" style="color: #000000;"></i></button>
        <button class="btn dettamex mb-3" style="visibility: hidden;" id="dettamex" data-bs-toggle="collapse" type="button" data-bs-target="#dettaglioamex"><i class="fa-solid fa-circle-info fa-lg" style="color: #000000;"></i></button>
        <div class="collapse" id="dettaglioamex">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    $('#insertamex').click(function() {
      var idtransamex = $('#idbudgettable').text()
      $('#idtransamex').text(idtransamex);

      console.log(idtransamex)
      $('#VistaDettaglioCategorieMese').modal('hide')
      $('#ModalInsertAmex').modal('show')
    })

    $('.dettamex').click(function(){
      var idtransamex = $('#idbudgettable').text()
      $.ajax({
            type: 'POST',
            url: './php/preleva_dati_dettagliotransamex.php',
            data: {
                ajax: 1,
                idtransamex:idtransamex

            },
            success: function(response) {
                $("#dettaglioamex").html(response);
            }
        });
      
    })

  })
</script>