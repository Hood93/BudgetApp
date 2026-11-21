<?php
// Shared visual shell. Keep existing route variables and legacy AJAX hooks intact.
$baCurrentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
$baLinks = [
 ['Homepage.php', 'Home', 'fa-chart-pie', $GLOBALS['Homepage'] ?? 'Homepage.php'],
 ['Transazioni.php', 'Transazioni Totali', 'fa-arrow-right-arrow-left', $GLOBALS['Transazioni'] ?? 'Transazioni.php'],
 ['TransazioniMese.php', 'Transazioni Mese', 'fa-calendar-days', $GLOBALS['TransazioniMese'] ?? 'TransazioniMese.php'],
 ['TransazioniPending.php', 'Transazioni Pending', 'fa-clock', $GLOBALS['TransazioniPending'] ?? 'TransazioniPending.php'],
 ['Gestione.php', 'Gestione', 'fa-sliders', $GLOBALS['Gestione'] ?? 'Gestione.php'],
];
$baTitle = ['Homepage.php'=>'Dashboard','Gestione.php'=>'Gestione budget','Transazioni.php'=>'Transazioni totali','TransazioniMese.php'=>'Transazioni mensili','TransazioniPending.php'=>'Transazioni in sospeso'][$baCurrentPage] ?? pathinfo($baCurrentPage, PATHINFO_FILENAME);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/app-shell.css">
<div class="ba-overlay" id="baOverlay" aria-hidden="true"></div>
<aside class="ba-sidebar" id="baSidebar" aria-label="Navigazione principale">
  <a class="ba-brand" href="<?php echo htmlspecialchars($GLOBALS['Homepage'] ?? 'Homepage.php', ENT_QUOTES, 'UTF-8'); ?>"><span class="ba-brand-icon"><i class="fa-solid fa-wallet"></i></span>BudgetApp</a>
  <div class="ba-nav-label">Menu principale</div>
  <nav class="ba-nav nav flex-column">
    <?php foreach ($baLinks as [$page, $label, $icon, $href]): ?>
      <a class="nav-link <?php echo $baCurrentPage === $page ? 'active' : ''; ?>" <?php echo $baCurrentPage === $page ? 'aria-current="page"' : ''; ?> href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">
        <i class="fa-solid <?php echo $icon; ?>" aria-hidden="true"></i><span><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php if ($page === 'TransazioniPending.php'): ?><span class="badge text-bg-danger" id="count" aria-label="Transazioni in sospeso"></span><?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>
  <div class="ba-footer"><i class="fa-solid fa-shield-halved me-2"></i>Gestione finanziaria</div>
</aside>
<header class="ba-topbar">
  <div class="d-flex align-items-center gap-3"><button class="ba-mobile-toggle" type="button" id="baMenuToggle" aria-controls="baSidebar" aria-expanded="false" aria-label="Apri menu"><i class="fa-solid fa-bars"></i></button><div><h1 class="ba-page-title"><?php echo htmlspecialchars($baTitle, ENT_QUOTES, 'UTF-8'); ?></h1><div class="ba-topbar-note">Panoramica delle tue finanze</div></div></div>
  <span class="d-none d-sm-inline-flex align-items-center gap-2 text-secondary small"><i class="fa-regular fa-calendar"></i><?php echo date('d/m/Y'); ?></span>
</header>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>
(function(){
  const sidebar=document.getElementById('baSidebar'),overlay=document.getElementById('baOverlay'),toggle=document.getElementById('baMenuToggle');
  function setOpen(open){sidebar.classList.toggle('ba-open',open);overlay.classList.toggle('ba-open',open);toggle.setAttribute('aria-expanded',String(open));}
  toggle.addEventListener('click',()=>setOpen(!sidebar.classList.contains('ba-open')));
  overlay.addEventListener('click',()=>setOpen(false));
  document.addEventListener('keydown',e=>{if(e.key==='Escape')setOpen(false)});
  $(function(){
    const pathname=window.location.pathname.split('/');
    const base=pathname.length>2?'/'+pathname[1]+'/':'/';
    const url=base+'php/count/counttransazionipending.php';
    $.get(url,function(data){const count=$('#count');count.text(data);if(Number(data)===0)count.removeClass('text-bg-danger').addClass('text-bg-secondary');});
  });
  $.ajax({type:'POST',url:'./php/disoperazioneric.php',data:{ajax:1}});
})();
</script>