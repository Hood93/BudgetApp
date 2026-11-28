<div class="modal fade ba-ass-insert" id="ModalNewCatAssociativa" tabindex="-1" aria-labelledby="ModalNewCatAssociativaTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
  <div class="modal-content">
   <div class="modal-header">
    <div><h5 class="modal-title" id="ModalNewCatAssociativaTitle">Nuova associazione categoria</h5><p class="ba-ass-help mb-0">Assegna automaticamente una categoria a una descrizione registrata.</p></div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
   </div>
   <form action="" method="POST" class="ba-ass-form">
    <div class="modal-body">
     <div class="ba-ass-field">
      <label class="form-label" for="newdescrizioneass">Descrizione da riconoscere</label>
      <input type="text" name="newdescrizioneass" id="newdescrizioneass" class="form-control" placeholder="Es. Rifornimento Auto" list="baAssocDescriptionOptions" required>
      <datalist id="baAssocDescriptionOptions"></datalist>
      <small class="ba-ass-help">Scegli una descrizione già registrata oppure digitane una nuova.</small>
     </div>
     <div class="ba-ass-field">
      <label class="form-label" for="newcategoriaassociata">Categoria da assegnare</label>
      <input type="text" name="newcategoriaassociata" id="newcategoriaassociata" class="form-control" placeholder="Es. Automobile" list="baAssocCategoryOptions" required>
      <datalist id="baAssocCategoryOptions"><?php foreach ($catarray as $category): ?><option value="<?= htmlspecialchars((string)$category, ENT_QUOTES, 'UTF-8') ?>"></option><?php endforeach; ?></datalist>
      <small class="ba-ass-help">Scegli o digita il nome della categoria.</small>
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
     <button type="button" id="closebtninsnewcatass" class="btn btn-secondary closebtninsnewcatass" data-bs-dismiss="modal">Annulla</button>
     <button type="submit" class="btn btn-primary savebtnnewcatass"><i class="fa-solid fa-check me-1" aria-hidden="true"></i> Salva associazione</button>
    </div>
   </form>
  </div>
 </div>
</div>
<script>
$(function(){
 const modal=document.getElementById('ModalNewCatAssociativa');
 const form=modal.querySelector('form');
 const source=document.getElementById('newdescrizioneass');
 const target=document.getElementById('newcategoriaassociata');
 const error=modal.querySelector('[data-ass-error]');
 const save=form.querySelector('button[type="submit"]');
 let saving=false, lookupId=0;
 function fillOptions(id,values){
  const list=document.getElementById(id);
  const items=Array.from(new Set(values.filter(value=>typeof value==='string'&&value.trim()))).sort((a,b)=>a.localeCompare(b,'it'));
  list.replaceChildren(...items.map(value=>{const option=document.createElement('option');option.value=value;return option;}));
 }
 function refreshOptions(){
  const id=++lookupId;
  $.ajax({url:'php/preleva_dati_suggerimenti_associativi.php',dataType:'json',cache:false}).done(data=>{
   if(id!==lookupId||!Array.isArray(data.descriptions)||!Array.isArray(data.mappings)||!Array.isArray(data.categories))return;
   fillOptions('baAssocDescriptionOptions',[...data.descriptions,...data.mappings].map(row=>row.description));
   fillOptions('baAssocCategoryOptions',data.categories.map(row=>row.category));
  });
 }
 function preview(){
  modal.querySelector('[data-preview-source]').textContent=source.value.trim()||'Descrizione da riconoscere';
  modal.querySelector('[data-preview-target]').textContent=target.value.trim()||'Categoria da assegnare';
 }
 source.addEventListener('input',preview);
 target.addEventListener('input',preview);
 modal.addEventListener('shown.bs.modal',()=>{error.hidden=true;refreshOptions();preview();source.focus();});
 modal.addEventListener('hide.bs.modal',event=>{if(saving)event.preventDefault();});
 modal.addEventListener('hidden.bs.modal',()=>{
  bootstrap.Modal.getOrCreateInstance(document.getElementById('ModalTabAssociative')).show();
 });
 form.addEventListener('submit',event=>{
  event.preventDefault();
  if(saving)return;
  if(!source.value.trim()||!target.value.trim()){error.textContent='Compila entrambi i campi.';error.hidden=false;return;}
  saving=true;save.disabled=true;error.hidden=true;
  $.ajax({type:'POST',url:'php/insupdcatassociativa.php',data:{ajax:1,editidcatassociativa:'',newdescrizioneass:source.value,newcategoriaassociata:target.value}})
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
