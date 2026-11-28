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
      
      <small class="ba-ass-help">Scegli una descrizione già censita oppure digita un nuovo valore.</small>
      <div class="ba-ass-picker" data-ass-picker>
       <div class="ba-ass-picker-tabs" role="group" aria-label="Tipo di destinazione">
        <button type="button" class="btn btn-outline-secondary btn-sm" data-picker-mode="existing" aria-pressed="true">Già censite</button>
        <button type="button" class="btn btn-outline-secondary btn-sm" data-picker-mode="new" aria-pressed="false">Nuova descrizione</button>
       </div>
       <div data-picker-existing>
        <label class="visually-hidden" for="baAssDestinationSearch">Cerca descrizioni e categorie</label>
        <input type="search" class="form-control" id="baAssDestinationSearch" placeholder="Cerca descrizione o categoria…">
        <div class="ba-ass-suggestions" data-picker-results aria-live="polite"></div>
       </div>
       <small class="ba-ass-help" data-picker-new hidden>Digita la nuova descrizione nel campo sopra. Potrai assegnarle una categoria nella sezione Categorie.</small>
      </div>
     </div>
     <section class="ba-ass-preview" aria-label="Anteprima associazione">
      <div class="ba-ass-help"><i class="fa-solid fa-eye me-1" aria-hidden="true"></i> Anteprima della regola</div>
      <div class="ba-ass-preview-source" data-preview-source>—</div>
      <i class="fa-solid fa-arrow-down ba-ass-help" aria-hidden="true"></i>
      <div class="ba-ass-preview-target" data-preview-target>—</div>
      <div class="ba-ass-help" data-preview-category></div>
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
 let catalog={entries:[],icons:new Map()}, pickerMode='existing', lookupState='idle', requestId=0;
 const picker=modal.querySelector('[data-ass-picker]');
 const search=modal.querySelector('#baAssDestinationSearch');
 const results=modal.querySelector('[data-picker-results]');
 function buildCatalog(data){
 const descriptions=new Map(), mapping=new Map(), icons=new Map();
 for(const row of data.descriptions||[]){
  if(typeof row.description!=='string'||!row.description.trim())continue;
  descriptions.set(row.description,(descriptions.get(row.description)||0)+1);
 }
 for(const row of data.mappings||[]){
  if(typeof row.description!=='string'||!row.description.trim())continue;
  if(!descriptions.has(row.description))descriptions.set(row.description,0);
  if(typeof row.category!=='string'||!row.category.trim())continue;
  if(!mapping.has(row.description))mapping.set(row.description,new Set());
  mapping.get(row.description).add(row.category);
 }
 for(const row of data.categories||[])if(typeof row.category==='string')icons.set(row.category,row.icon);
 const entries=Array.from(descriptions,([name,count])=>({name,count,categories:Array.from(mapping.get(name)||[]).sort((a,b)=>a.localeCompare(b,'it'))}));
 entries.sort((a,b)=>b.count-a.count||a.name.localeCompare(b.name,'it'));
 return {entries,icons};
}
 function iconFor(category){
  const icon=document.createElement('i');
  const match=String(catalog.icons.get(category)||'').match(/class\s*=\s*["']([^"']+)["']/i);
  const tokens=match?match[1].trim().split(/\s+/):[];
  icon.className=tokens.length&&tokens.every(token=>/^(?:fa|fas|far|fab|fa-[a-z0-9-]+)$/i.test(token))?tokens.join(' '):'fa-solid fa-folder';
  icon.setAttribute('aria-hidden','true');
  return icon;
 }
 function fold(value){return String(value).toLocaleLowerCase('it');}
 function categoryText(entry){return entry.categories.length?entry.categories.join(' · '):'Senza categoria associata';}
 function renderSuggestions(){
  results.replaceChildren();
  if(lookupState==='loading'||lookupState==='idle'){results.textContent='Caricamento suggerimenti…';return;}
  if(lookupState==='error'){
   results.textContent='Suggerimenti non disponibili. Puoi digitare la destinazione manualmente. ';
   const retry=document.createElement('button');retry.type='button';retry.className='btn btn-outline-secondary btn-sm';retry.textContent='Riprova';retry.addEventListener('click',loadSuggestions);results.append(retry);return;
  }
  const term=fold(search.value.trim());
  const entries=catalog.entries.filter(entry=>fold(entry.name+' '+categoryText(entry)).includes(term));
  if(!entries.length){results.textContent='Nessuna descrizione trovata. Puoi inserirne una nuova.';return;}
  function addSection(title,items,category=null){
   const section=document.createElement('section');section.className='ba-ass-suggestion-group';
   const heading=document.createElement('div');heading.className='ba-ass-suggestion-title';
   if(category!==null)heading.append(iconFor(category));
   heading.append(document.createTextNode(title));section.append(heading);
   for(const entry of items){
    const button=document.createElement('button');button.type='button';button.className='ba-ass-suggestion';
    button.setAttribute('aria-pressed',String(target.value===entry.name));
    const label=document.createElement('span');label.textContent=entry.name;
    const meta=document.createElement('small');meta.className='ba-ass-help';meta.textContent=entry.count+' '+(entry.count===1?'regola':'regole')+(category===null?' · '+categoryText(entry):'');
    label.append(meta);button.append(label);
    const check=document.createElement('i');check.className=target.value===entry.name?'fa-solid fa-circle-check':'fa-regular fa-circle';check.setAttribute('aria-hidden','true');button.append(check);
    button.addEventListener('click',()=>{target.value=entry.name;preview();renderSuggestions();});
    section.append(button);
   }
   results.append(section);
  }
  if(!term){const frequent=entries.filter(entry=>entry.count>0).slice(0,5);if(frequent.length)addSection('Più utilizzate',frequent);}
  const groups=new Map();
  for(const entry of entries){
   for(const category of entry.categories.length?entry.categories:[null]){
    if(!groups.has(category))groups.set(category,[]);
    groups.get(category).push(entry);
   }
  }
  const names=Array.from(groups.keys()).sort((a,b)=>a===null?1:b===null?-1:a.localeCompare(b,'it'));
  for(const category of names)addSection(category===null?'Senza categoria associata':category,groups.get(category),category);
 }
 function setMode(mode){
  pickerMode=mode;
  picker.querySelectorAll('[data-picker-mode]').forEach(button=>button.setAttribute('aria-pressed',String(button.dataset.pickerMode===mode)));
  picker.querySelector('[data-picker-existing]').hidden=mode!=='existing';
  picker.querySelector('[data-picker-new]').hidden=mode!=='new';
  if(mode==='existing')renderSuggestions();
 }
 function loadSuggestions(){
  const id=++requestId;lookupState='loading';renderSuggestions();
  $.ajax({url:'php/preleva_dati_suggerimenti_associativi.php',dataType:'json',cache:false})
   .done(data=>{if(id!==requestId)return;if(!Array.isArray(data.descriptions)||!Array.isArray(data.mappings)||!Array.isArray(data.categories)){lookupState='error';}else{catalog=buildCatalog(data);lookupState='ready';}preview();renderSuggestions();})
   .fail(()=>{if(id!==requestId)return;lookupState='error';preview();renderSuggestions();});
 }
 picker.querySelectorAll('[data-picker-mode]').forEach(button=>button.addEventListener('click',()=>setMode(button.dataset.pickerMode)));
 search.addEventListener('input',renderSuggestions);
 function preview(){
  modal.querySelector('[data-preview-source]').textContent=source.value.trim()||'Descrizione bancaria originale';
  modal.querySelector('[data-preview-target]').textContent=target.value.trim()||'Descrizione da registrare';
  const entry=catalog.entries.find(item=>item.name===target.value);
  const info=modal.querySelector('[data-preview-category]');
  info.textContent=!target.value.trim()?'':lookupState!=='ready'?'Categoria non verificata':entry&&entry.categories.length?'Categoria già associata: '+categoryText(entry):'Nessuna categoria associata';
 }
 source.addEventListener('input',preview);
 target.addEventListener('input',()=>{preview();if(pickerMode==='existing'){search.value=target.value;renderSuggestions();}});
 modal.addEventListener('shown.bs.modal',()=>{error.hidden=true;search.value='';setMode('existing');loadSuggestions();preview();source.focus();});
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
