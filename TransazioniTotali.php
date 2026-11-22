<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <!-- CDN Bootstrap -->
    <!-- Font Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- End of Font Link -->
    <!-- Card Link -->
    <link rel="stylesheet" href="css/card.css">
    <!-- End of Card Link -->
    <title>Transazioni Totali</title>
</head>

<body>
    <?php
    include "gvariable.php"; // File Variabili
    include "php/utility.php"; // File Utility
    include "componenti/navbar.php"; // File Navbar
    ?>
    <main class="container-fluid ba-transactions-page">
        <div class="container-fluid">
            <?php include "componenti/Modal/ModalEditOperazione.php"; // File Modal Edit 
            include "componenti/Modal/ModalInsertOperazione.php"; // File Modal Edit 
            ?>
            <section class="ba-welcome"><div class="ba-eyebrow">MOVIMENTI</div><h2>Tutte le transazioni</h2><p>Consulta, cerca e filtra i movimenti registrati.</p></section>
            <section class="row g-3 ba-transactions-summary mb-3" aria-label="Riepilogo transazioni filtrate">
                <div class="col-4"><div class="ba-panel ba-summary-tile"><span>Entrate</span><strong id="baIncome">—</strong></div></div>
                <div class="col-4"><div class="ba-panel ba-summary-tile"><span>Uscite</span><strong id="baExpense">—</strong></div></div>
                <div class="col-4"><div class="ba-panel ba-summary-tile"><span>Saldo</span><strong id="baBalance">—</strong></div></div>
            </section>
            <section class="ba-panel ba-filter-panel mb-4">
              <div class="row g-3 align-items-end">
                <div class="col-12 col-lg-8"><label class="form-label" for="baSearch">Ricerca transazioni</label><div class="input-group"><span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span><input id="baSearch" type="search" class="form-control" placeholder="Cerca descrizione, importo o categoria..."></div></div>
                <div class="col-12 col-lg-4"><button class="btn btn-primary w-100" name="add" id="add"><i class="fa-solid fa-plus me-2"></i>Aggiungi transazione</button></div>
                <div class="col-12"><div class="d-flex gap-2 align-items-center flex-wrap"><button type="button" class="btn btn-outline-secondary btn-sm" id="baToggleFilters" aria-expanded="false" aria-controls="baAdvancedFilters"><i class="fa-solid fa-sliders me-1"></i> Filtri avanzati</button><button type="button" class="btn btn-link btn-sm" id="baClearFilters">Azzera filtri</button><span class="ms-auto text-secondary small" id="baResultCount" aria-live="polite"></span></div></div>
                <div class="col-12" id="baAdvancedFilters" hidden>
                  <div class="row g-3">
                    <div class="col-6 col-lg-3"><label class="form-label" for="filtroanno">Anno</label><select class="form-select" id="filtroanno"><option value="">Tutti</option><?php foreach ($tableanno as $anno): ?><option value="<?php echo htmlspecialchars((string)$anno, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$anno, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?></select></div>
                    <div class="col-6 col-lg-3"><label class="form-label" for="filtromese">Mese</label><select class="form-select" id="filtromese"><option value="">Tutti</option><?php foreach ($mesi as $mese): ?><option value="<?php echo htmlspecialchars((string)$mese, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$mese, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?></select></div>
                    <div class="col-6 col-lg-3"><label class="form-label" for="filtrotag1">Tag</label><input type="text" id="filtrotag1" class="form-control" placeholder="Filtra per tag"></div>
                    <div class="col-6 col-lg-3"><label class="form-label" for="filtrocategoria">Categoria</label><select class="form-select" id="filtrocategoria"><option value="">Tutte</option><?php foreach ($catarray as $categoria): ?><option value="<?php echo htmlspecialchars((string)$categoria, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$categoria, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?></select></div>
                    <div class="col-12"><label class="form-label" for="filtrotipo">Tipo movimento</label><select class="form-select" id="filtrotipo"><option value="">Entrate e uscite</option><option value="Entrata">Solo entrate</option><option value="Uscita">Solo uscite</option></select></div>
                  </div>
                </div>
              </div>
            </section>
        </div>
        <div class="ba-results-panel" id="transazionitotali" aria-live="polite"><div class="ba-transaction-list" id="baTransactionList"></div></div>
        <div class="ba-load-footer" id="baLoadFooter">
            <div id="baLoadStatus" class="text-secondary small" role="status"></div>
            <button type="button" class="btn btn-outline-secondary" id="baLoadMore" hidden>Carica altre transazioni</button>
            <div id="baLoadSentinel" aria-hidden="true"></div>
        </div>
    </main>

    <script>
    (function () {
        let request = null, timer = null, sequence = 0, cursor = null, hasMore = true, busy = false, lastPeriod = '';
        const months = ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
        const money = value => new Intl.NumberFormat('it-IT',{style:'currency',currency:'EUR'}).format(value);
        const filters = () => ({
            ajax: 1, cerca: $('#baSearch').val() || '', anno: $('#filtroanno').val() || '',
            mese: $('#filtromese').val() || '', tag1: $('#filtrotag1').val() || '',
            categoria: $('#filtrocategoria').val() || '', tipo: $('#filtrotipo').val() || ''
        });
        function addRows(html) {
            const $rows = $($.parseHTML(html, document, false) || []);
            $rows.each(function () {
                const period = this.getAttribute('data-period');
                if (period && period !== lastPeriod) {
                    const parts = period.split('-');
                    const heading = document.createElement('h3');
                    heading.className = 'ba-period-heading';
                    heading.textContent = (months[Number(parts[1])-1] || period) + ' ' + parts[0];
                    $('#baTransactionList').append(heading);
                    lastPeriod = period;
                }
                $('#baTransactionList').append(this);
            });
        }
        function load(reset) {
            if (busy && !reset) return;
            if (!reset && !hasMore) return;
            if (reset) {
                sequence++;
                if (request) request.abort();
                cursor = null; hasMore = true; lastPeriod = '';
                $('#baTransactionList').empty();
                $('#baIncome,#baExpense,#baBalance').text('—');
                $('#baResultCount').text('');
            }
            const current = sequence;
            busy = true;
            $('#baLoadMore').prop('disabled',true).attr('hidden',true);
            $('#baLoadStatus').text('Caricamento transazioni...');
            $('#transazionitotali').attr('aria-busy','true');
            request = $.ajax({
                type:'POST',url:'./php/preleva_dati_budget.php',dataType:'json',
                data:Object.assign(filters(),{cursor:cursor || ''})
            }).done(function (data) {
                if (current !== sequence) return;
                if (!data || !data.ok) throw Error('Invalid transaction response');
                addRows(data.html);
                cursor = data.nextCursor;
                hasMore = !!data.hasMore;
                $('#baIncome').text(money(data.income));
                $('#baExpense').text(money(data.expense));
                $('#baBalance').text(money(data.income-data.expense));
                $('#baResultCount').text(data.count + ' movimenti');
                if (!data.count) $('#baTransactionList').html('<div class="ba-empty-state">Nessuna transazione trovata.</div>');
                $('#baLoadStatus').text(hasMore ? 'Scorri per visualizzare altre transazioni' : 'Tutte le transazioni caricate');
                $('#baLoadMore').attr('hidden',!hasMore);
            }).fail(function (xhr,status) {
                if (current !== sequence || status === 'abort') return;
                $('#baLoadStatus').text('Impossibile caricare le transazioni. Riprova.');
                $('#baLoadMore').attr('hidden',false);
            }).always(function () {
                if (current !== sequence) return;
                busy = false;
                $('#baLoadMore').prop('disabled',false);
                $('#transazionitotali').removeAttr('aria-busy');
            });
        }
        function refresh() { clearTimeout(timer); load(true); }
        function schedule() { clearTimeout(timer); timer=setTimeout(refresh,250); }
        window.cercacontrollo = schedule;
        window.filtratag1 = schedule;
        window.filtraanno = refresh;
        window.filtramese = refresh;
        window.filtracategoria = refresh;
        window.EditOperazione = function (button) {
            let data;
            try { data=JSON.parse(button.getAttribute('data-edit')); } catch(e) { return; }
            if (!Array.isArray(data) || data.length < 7) return;
            $('#editidoperazione').val(data[0]);
            $('#editdataoperazione').val(String(data[1]||'').replaceAll('/','-'));
            $('#editdescrizione').val(data[2]);
            $('#editentrate').val(data[3]);
            $('#edituscite').val(data[4]);
            $('#edittag1').val(data[5]);
            $('#editcategoria').val(data[6]);
        };
        $(function () {
            $('#baSearch,#filtrotag1').on('input',schedule);
            $('#filtroanno,#filtromese,#filtrocategoria,#filtrotipo').on('change',refresh);
            $('#baClearFilters').on('click',function () {
                $('#baSearch,#filtrotag1').val('');
                $('#filtroanno,#filtromese,#filtrocategoria,#filtrotipo').val('');
                refresh();
            });
            $('#baToggleFilters').on('click',function () {
                const panel=document.getElementById('baAdvancedFilters');
                panel.hidden=!panel.hidden;
                $(this).attr('aria-expanded',String(!panel.hidden));
            });
            $('#add').on('click',function () { $('#ModalNewOperazione').modal('show'); });
            $('#baLoadMore').on('click',function () { load(false); });
            $('#baTransactionList').on('click','.editbtn',function () { window.EditOperazione(this); });
            if ('IntersectionObserver' in window) {
                const observer=new IntersectionObserver(entries=>{
                    if (entries.some(entry=>entry.isIntersecting) && !busy && hasMore) load(false);
                },{rootMargin:'350px'});
                observer.observe(document.getElementById('baLoadSentinel'));
            }
            refresh();
        });
    })();
    </script>
</body>

</html>