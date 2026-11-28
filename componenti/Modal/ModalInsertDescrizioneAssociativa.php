<div class="modal fade ba-ass-insert" id="ModalNewDesAssociativa" tabindex="-1" aria-labelledby="ModalNewDesAssociativaTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
  <div class="modal-content">
   <div class="modal-header">
    <div><h5 class="modal-title" id="ModalNewDesAssociativaTitle">Nuova associazione descrizione</h5><p class="ba-ass-help mb-0">Definisci come registrare una descrizione proveniente dal conto corrente.</p></div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
   </div>
   <form action="" method="POST" class="ba-ass-form">
    <div class="modal-body">
     <div class="ba-ass-field">
      <label class="form-label" for="newolddescrizione">Descrizione bancaria originale</label>
      <input type="text" name="newolddescrizione" id="newolddescrizione" class="form-control" placeholder="Es. IPMATIC" required>
      <small class="ba-ass-help">Testo da riconoscere durante l’importazione.</small>
     </div>
     <div class="ba-ass-field">
      <label class="form-label" for="newnewdescrizione">Descrizione da registrare</label>
      <input type="text" name="newnewdescrizione" id="newnewdescrizione" class="form-control" placeholder="Es. Rifornimento Auto" required>
      
      <small class="ba-ass-help">Nome che verrà utilizzato in BudgetApp.</small>
     </div>
     <section class="ba-ass-preview" aria-label="Anteprima associazione">
      <div class="ba-ass-help"><i class="fa-solid fa-eye me-1" aria-hidden="true"></i> Anteprima della regola</div>
      <div class="ba-ass-preview-source" data-preview-source>—</div>
      <i class="fa-solid fa-arrow-down ba-ass-help" aria-hidden="true"></i>
      <div class="ba-ass-preview-target" data-preview-target>—</div>
     </section>
     <div class="alert alert-danger mt-3 mb-0" role="alert" data-ass-error hidden></div>
    </div>
    <div class="modal-footer">
     <button type="button" id="closebtnnewinsdesass" class="btn btn-secondary closebtnnewinsdesass" data-bs-dismiss="modal">Annulla</button>
     <button type="submit" class="btn btn-primary savebtnnewdesass"><i class="fa-solid fa-check me-1" aria-hidden="true"></i> Salva associazione</button>
    </div>
   </form>
  </div>
 </div>
</div>
<script>
$(function(){
 const modal=document.getElementById('ModalNewDesAssociativa');
 const form=modal.querySelector('form');
 const source=document.getElementById('newolddescrizione');
 const target=document.getElementById('newnewdescrizione');
 const error=modal.querySelector('[data-ass-error]');
 const save=form.querySelector('button[type="submit"]');
 let saving=false;
 function preview(){
  modal.querySelector('[data-preview-source]').textContent=source.value.trim()||'Descrizione bancaria originale';
  modal.querySelector('[data-preview-target]').textContent=target.value.trim()||'Descrizione da registrare';
 }
 source.addEventListener('input',preview);
 target.addEventListener('input',preview);
 modal.addEventListener('shown.bs.modal',()=>{error.hidden=true;preview();source.focus();});
 modal.addEventListener('hide.bs.modal',event=>{if(saving)event.preventDefault();});
 modal.addEventListener('hidden.bs.modal',()=>{
  bootstrap.Modal.getOrCreateInstance(document.getElementById('ModalTabAssociative')).show();
 });
 form.addEventListener('submit',event=>{
  event.preventDefault();
  if(saving)return;
  if(!source.value.trim()||!target.value.trim()){error.textContent='Compila entrambi i campi.';error.hidden=false;return;}
  saving=true;save.disabled=true;error.hidden=true;
  $.ajax({type:'POST',url:'php/insupddesassociativa.php',data:{ajax:1,editiddesassociativa:'',newolddescrizione:source.value,newnewdescrizione:target.value}})
   .done(response=>{
    saving=false;
    if(String(response).trim()!=='Data Inserted'){error.textContent='Salvataggio non confermato. Controlla i dati e riprova.';error.hidden=false;return;}
    form.reset();preview();bootstrap.Modal.getOrCreateInstance(modal).hide();
   }).fail(()=>{error.textContent='Impossibile salvare l’associazione. Riprova.';error.hidden=false;})
   .always(()=>{saving=false;save.disabled=false;});
 });
 preview();
});
</script>
