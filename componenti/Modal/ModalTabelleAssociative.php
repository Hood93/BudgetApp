<style>
#ModalTabAssociative .modal-dialog{max-width:min(1100px,calc(100% - 24px));margin-inline:auto}
#ModalTabAssociative .modal-body{min-width:0;padding:20px;overflow-y:auto;text-align:left}
#ModalTabAssociative .ba-ass-toolbar{display:grid;gap:12px;margin-bottom:16px}
#ModalTabAssociative .ba-ass-tabs{display:flex;gap:8px;flex-wrap:wrap}
#ModalTabAssociative .ba-ass-tabs button[aria-selected=true]{background:var(--bs-primary);color:white;border-color:var(--bs-primary)}
#ModalTabAssociative .ba-ass-panel{min-width:0;max-width:100%}
#ModalTabAssociative .ba-ass-panel[hidden],#ModalTabAssociative tr[hidden]{display:none!important}
#ModalTabAssociative .ba-ass-panel .table-responsive{border:1px solid var(--ba-border,#40516a);border-radius:14px;max-width:100%;padding:12px;overflow-x:auto}
#ModalTabAssociative .ba-ass-panel table{width:100%;min-width:0!important;margin:12px 0 0;table-layout:fixed;border-collapse:separate;border-spacing:0 6px;border:0!important}
#ModalTabAssociative .ba-ass-panel th,#ModalTabAssociative .ba-ass-panel td{border:0!important;padding:12px;overflow-wrap:anywhere;vertical-align:middle}
#ModalTabAssociative .ba-ass-panel th:last-child,#ModalTabAssociative .ba-ass-panel td:last-child{width:76px;text-align:center}
#ModalTabAssociative .ba-ass-panel tbody td:nth-child(2){color:var(--ba-muted,inherit)}
#ModalTabAssociative .ba-ass-panel tbody td:nth-child(3){font-weight:650}
#ModalTabAssociative .ba-ass-panel tbody td:nth-child(2){border-radius:10px 0 0 10px}
#ModalTabAssociative .ba-ass-panel tbody td:last-child{border-radius:0 10px 10px 0}
#ModalTabAssociative .ba-ass-panel tbody button{min-width:44px;min-height:44px}
#ModalTabAssociative .ba-ass-panel .addbtndesass,#ModalTabAssociative .ba-ass-panel .addbtndcatass{display:inline-flex;align-items:center;gap:8px;padding:9px 14px}
@media(max-width:600px){
#ModalTabAssociative .modal-body{padding:12px}
#ModalTabAssociative .ba-ass-panel .table-responsive{padding:8px;overflow-x:visible}
#ModalTabAssociative .ba-ass-panel table,#ModalTabAssociative .ba-ass-panel tbody{display:block;width:100%;min-width:0!important}
#ModalTabAssociative .ba-ass-panel thead{display:none}
#ModalTabAssociative .ba-ass-panel tbody tr{display:grid;grid-template-columns:minmax(0,1fr) 48px;gap:4px 8px;padding:12px;margin-bottom:8px;border:1px solid var(--ba-border,#40516a);border-radius:12px;min-width:0}
#ModalTabAssociative .ba-ass-panel tbody td{display:block;width:auto!important;min-width:0;padding:4px!important;background:transparent!important;box-shadow:none!important}
#ModalTabAssociative .ba-ass-panel tbody td:first-child{display:none!important}
#ModalTabAssociative .ba-ass-panel tbody td:nth-child(2){grid-column:1;grid-row:1;font-size:.85rem}
#ModalTabAssociative .ba-ass-panel tbody td:nth-child(3){grid-column:1;grid-row:2}
#ModalTabAssociative .ba-ass-panel tbody td:last-child{grid-column:2;grid-row:1 / span 2;align-self:center}
}
#ModalTabAssociative .ba-ass-groups{display:grid;gap:10px;margin-top:12px}
#ModalTabAssociative .ba-ass-groups[hidden],#ModalTabAssociative table[hidden]{display:none!important}
#ModalTabAssociative .ba-ass-group{border:1px solid var(--ba-border,#40516a);border-radius:12px;padding:12px;min-width:0}
#ModalTabAssociative .ba-ass-group summary{cursor:pointer;overflow-wrap:anywhere;font-weight:650}
#ModalTabAssociative .ba-ass-group-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 0;border-top:1px solid var(--ba-border,#40516a)}
#ModalTabAssociative .ba-ass-group-row span{min-width:0;overflow-wrap:anywhere}
#ModalTabAssociative .ba-ass-group-row button{flex-shrink:0;min-width:44px;min-height:44px}
</style>
<div class="modal fade" id="ModalTabAssociative" tabindex="-1" aria-labelledby="baAssTitle" aria-hidden="true">
 <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
  <div class="modal-header"><div><h5 class="modal-title" id="baAssTitle">Regole di associazione</h5><small class="text-secondary">Normalizza e categorizza le transazioni bancarie</small></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button></div>
  <div class="modal-body">
   <div class="ba-ass-toolbar">
    <div class="ba-ass-tabs" role="group" aria-label="Tipo di associazione"><button type="button" class="btn btn-outline-secondary" data-ass-tab="desc" aria-selected="true">Descrizioni</button><button type="button" class="btn btn-outline-secondary" data-ass-tab="cat" aria-selected="false">Categorie</button></div>
    <div class="ba-ass-tabs" role="group" aria-label="Visualizzazione"><button type="button" class="btn btn-outline-secondary" data-ass-view="list" aria-selected="true">Elenco</button><button type="button" class="btn btn-outline-secondary" data-ass-view="group" aria-selected="false">Raggruppate</button></div>
    <label for="baAssSearch" class="visually-hidden">Cerca associazioni</label><input id="baAssSearch" class="form-control" type="search" placeholder="Cerca descrizione o categoria…">
    <small id="baAssCount" class="text-secondary" aria-live="polite"></small>
   </div>
   <section class="ba-ass-panel" id="tabelladescassociative" aria-label="Associazioni descrizioni"></section>
   <section class="ba-ass-panel" id="tabellacatassociative" aria-label="Associazioni categorie" hidden></section>
  </div>
  <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button></div>
 </div></div>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>
$(function(){
 const modal=document.getElementById('ModalTabAssociative');
 const panels={desc:document.getElementById('tabelladescassociative'),cat:document.getElementById('tabellacatassociative')};
 const search=document.getElementById('baAssSearch');
 let active='desc', view='list';
 const expanded={desc:new Set(),cat:new Set()};
 function renderGroups(panel,rows){
  let container=panel.querySelector('.ba-ass-groups');
  if(!container){container=document.createElement('div');container.className='ba-ass-groups';(panel.querySelector('.table-responsive')||panel).append(container);}
  container.replaceChildren();
  container.hidden=view!=='group';
  const table=panel.querySelector('table');
  if(table)table.hidden=view==='group';
  if(view!=='group')return;
  const groups=new Map();
  rows.filter(row=>!row.hidden&&row.cells.length>=4).forEach(row=>{
   const key=row.cells[2].textContent;
   if(!groups.has(key))groups.set(key,[]);
   groups.get(key).push(row);
  });
  for(const [key,items] of groups){
   const details=document.createElement('details');details.className='ba-ass-group';details.open=expanded[active].has(key);
   const tab=active;
   details.addEventListener('toggle',()=>{if(details.open)expanded[tab].add(key);else expanded[tab].delete(key);});
   const summary=document.createElement('summary');summary.textContent=key+' · '+items.length+' associazioni';details.append(summary);
   for(const row of items){
    const line=document.createElement('div');line.className='ba-ass-group-row';
    const label=document.createElement('span');label.textContent=row.cells[1].textContent;line.append(label);
    const original=row.querySelector('.editbtndesass,.editbtncatassociativa');
    if(original){const button=document.createElement('button');button.type='button';button.className='btn btn-outline-secondary btn-sm';button.setAttribute('aria-label','Modifica '+label.textContent);button.innerHTML='<i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>';button.addEventListener('click',()=>original.click());line.append(button);}
    details.append(line);
   }
   container.append(details);
  }
  if(!groups.size)container.textContent='Nessuna associazione trovata.';
 }
 function filter(){
  const rows=Array.from(panels[active].querySelectorAll('tbody tr'));
  const term=search.value.trim().toLocaleLowerCase('it');
  let count=0;
  rows.forEach(row=>{const cells=row.cells;const value=[cells[1]?.textContent,cells[2]?.textContent].join(' ').toLocaleLowerCase('it');row.hidden=!value.includes(term);if(!row.hidden)count++;});
  document.getElementById('baAssCount').textContent=count+' / '+rows.length+' associazioni';
  renderGroups(panels[active],rows);
 }
 function decorate(panel){
  const add=panel.querySelector('.addbtndesass,.addbtndcatass');
  if(add){add.innerHTML='<i class="fa-solid fa-plus" aria-hidden="true"></i> Nuova associazione';}
  panel.querySelectorAll('.editbtndesass,.editbtncatassociativa').forEach(b=>b.setAttribute('aria-label','Modifica associazione'));
  const headers=panel.querySelectorAll('thead th');
  if(panel===panels.desc&&headers.length>=2){headers[0].textContent='Descrizione bancaria';headers[1].textContent='Descrizione registrata';}
  filter();
 }
 function load(key,url){
  const panel=panels[key];panel.textContent='Caricamento…';
  $.ajax({type:'POST',url:url,data:{ajax:1},success:function(response){$(panel).html(response);decorate(panel);},error:function(){panel.textContent='Impossibile caricare le associazioni. Riapri la finestra per riprovare.';filter();}});
 }
 modal.querySelectorAll('[data-ass-tab]').forEach(button=>button.addEventListener('click',()=>{
  active=button.dataset.assTab;
  Object.keys(panels).forEach(key=>panels[key].hidden=key!==active);
  modal.querySelectorAll('[data-ass-tab]').forEach(b=>b.setAttribute('aria-selected',String(b===button)));
  filter();
 }));
 modal.querySelectorAll('[data-ass-view]').forEach(button=>button.addEventListener('click',()=>{
  view=button.dataset.assView;
  modal.querySelectorAll('[data-ass-view]').forEach(b=>b.setAttribute('aria-selected',String(b===button)));
  filter();
 }));
 search.addEventListener('input',filter);
 modal.addEventListener('shown.bs.modal',()=>{load('desc','./php/preleva_dati_descassociative.php');load('cat','./php/preleva_dati_catassociative.php');});
});
</script>
