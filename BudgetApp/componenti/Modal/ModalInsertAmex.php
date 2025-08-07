 <!-- Modal -->
 <div class="modal fade" id="ModalInsertAmex" role="dialog">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLabel">Inserisci Dettaglio Amex</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <form action="" method="POST">
         <div class="modal-body">
           <div class="form-group">
             <input type="text" name="idtransamex" id="idtransamex" class="form-control" hidden>
           </div>
           <div class="form-group">
             <label>Descrizione</label>
             <input type="text" name="newdescrizioneamex" id="newdescrizioneamex" class="form-control" placeholder="Inserire Descrizione"> 
           </div>
           <div class="form-group">
             <label>Importo</label>
             <input type="number" name="newimportoamex" id="newimportoamex" class="form-control" placeholder="Inserire Importo" value="0">
           </div>
           <div class="form-group">
             <label>Tipo Carta</label>
             <input type="text" name="newtipocartaamex" id="newtipocartaamex" class="form-control" placeholder="Inserire Tipo Carta" value="">
           </div>
         </div>
         <div class="modal-footer">
           <button type="button" id="closebtndettamex" class="btn btn-secondary closebtndettamex" data-bs-dismiss="modal">Close</button>
           <button type="button" id="savebtndettamex" class="btn btn-dark savebtndettamex">Save changes</button>
         </div>
     </div>
     </form>
   </div>
 </div>

 <!-- Modal -->

 <script>
   $(document).ready(function() {

     $('#savebtndettamex').click(function() {
      
       var idtransamex = $('#idtransamex').text()
       var descrizioneamex = $('#newdescrizioneamex').val()
       var importoamex = $('#newimportoamex').val()
       var newtipocartaamex = $('#newtipocartaamex').val()
       

       $.ajax({
         type: 'POST',
         url: './php/insupddettamex.php',
         data: {
           ajax: 1,
           idtransamex:idtransamex,
           descrizioneamex:descrizioneamex,
           importoamex:importoamex,
           newtipocartaamex:newtipocartaamex
         },
         success: function() {
          $('#closebtndettamex').click()
         }
       });


     })

   })
 </script>