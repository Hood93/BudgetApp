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
    <!-- CDN Icone-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDN Icone -->
    <link rel="stylesheet" href="css/graph.css">
    <title>Homepage</title>
</head>

<body>
    <!-- Container Principale -->
    <div class="container-fluid">
        <?php
        include "gvariable.php"; // File Variabili
        include "php/utility.php"; // File Utility
        include "componenti/navbar.php"; // File Navbar
        ?>
        <main class="ba-dashboard">
          <section class="ba-welcome d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div><div class="ba-eyebrow">PANORAMICA FINANZIARIA</div><h2>Il tuo budget, a colpo d'occhio</h2><p>Entrate, uscite e andamento del saldo durante l'anno.</p></div>
            <div class="ba-year-control"><label for="anno" class="form-label mb-1">Anno di riferimento</label>
              <select class="form-select" onchange="selanno(this)" name="anno" id="anno"><option value="">Tutti gli anni</option>
              <?php foreach ($tableanno as $anno): ?><option value="<?php echo htmlspecialchars((string)$anno, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$anno, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?>
              </select>
            </div>
          </section>
          <section class="ba-stats row g-3 mt-1" aria-label="Riepilogo annuale">
            <div class="col-12 col-md-4"><div class="ba-stat"><div class="ba-stat-icon ba-positive"><i class="fa-solid fa-arrow-trend-up"></i></div><div><div class="ba-stat-label">Entrate</div><div class="ba-stat-value" id="baEntrate">—</div><div class="ba-stat-caption">Totale periodo selezionato</div></div></div></div>
            <div class="col-12 col-md-4"><div class="ba-stat"><div class="ba-stat-icon ba-negative"><i class="fa-solid fa-arrow-trend-down"></i></div><div><div class="ba-stat-label">Uscite</div><div class="ba-stat-value" id="baUscite">—</div><div class="ba-stat-caption">Totale periodo selezionato</div></div></div></div>
            <div class="col-12 col-md-4"><div class="ba-stat"><div class="ba-stat-icon ba-neutral"><i class="fa-solid fa-wallet"></i></div><div><div class="ba-stat-label">Saldo</div><div class="ba-stat-value" id="baSaldo">—</div><div class="ba-stat-caption">Differenza entrate e uscite</div></div></div></div>
          </section>
          <section class="ba-insights row g-3 mt-1" aria-label="Indicatori mensili"><div class="col-12 col-md-6"><div class="ba-panel ba-insight"><div class="ba-stat-label">Saldo ultimo mese disponibile</div><div class="ba-insight-value" id="baLatestBalance">—</div><div class="ba-stat-caption" id="baLatestMonth">In attesa dei dati</div></div></div><div class="col-12 col-md-6"><div class="ba-panel ba-insight"><div class="ba-stat-label">Variazione spese rispetto al mese precedente</div><div class="ba-insight-value" id="baExpenseChange">—</div><div class="ba-stat-caption" id="baExpensePeriod">Confronto tra mesi consecutivi disponibili</div></div></div></section>
          <section class="ba-charts row g-4 mt-1">
            <div class="col-12 col-xl-7"><div class="ba-panel"><div class="ba-panel-heading"><div><div class="ba-eyebrow">MOVIMENTI</div><h3>Entrate e uscite</h3></div><i class="fa-solid fa-chart-column text-secondary"></i></div><div class="ba-chart-wrap"><canvas id="SpeseChart"></canvas></div></div></div>
            <div class="col-12 col-xl-5"><div class="ba-panel"><div class="ba-panel-heading"><div><div class="ba-eyebrow">ANDAMENTO</div><h3>Residuo mensile</h3></div><i class="fa-solid fa-chart-line text-secondary"></i></div><div class="ba-chart-wrap"><canvas id="ResiduoChart"></canvas></div></div></div>
          </section>
          <section class="ba-panel mt-4"><div class="ba-panel-heading"><div><div class="ba-eyebrow">RIEPILOGO</div><h3>Resoconto mensile</h3></div></div><div id="resmensile" class="ba-summary-content">Seleziona un anno per consultare il riepilogo.</div></section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </div>
    <!-- Container Principale -->
    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <!-- End of Jquery CDN -->
</body>
<script>
    $(document).ready(function() {
        if ($('#anno option[value="' + new Date().getFullYear() + '"]').length) $('#anno').val(String(new Date().getFullYear()));
        getresocontoannuale()
        ReminderTransazioni()



        //Prelevo Resoconto Annuale
        function getresocontoannuale() {
            $.ajax({

                type: 'POST',
                url: './php/json/preleva_dati_reportannualejson.php',
                dataType: 'JSON',
                data: {
                    ajax: 1,
                    cerca: $('#anno').val(),
                },

                success: function(response) {
                    if (!Array.isArray(response)) { $('#baEntrate, #baUscite, #baSaldo').text('Dati non disponibili'); return; }
                    creaGrafico(response)
                    ResiduiMensiliGrafico(response);
                    aggiornaRiepilogo(response);
                    aggiornaCategorie();
                },
                error: function() { $('#baEntrate, #baUscite, #baSaldo').text('Dati non disponibili'); }
            });
        }



    })

    //Funzione Selezione Anno Report
    function selanno(_this) {
        var cerca = $(_this).val();
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: './php/json/preleva_dati_reportannualejson.php',
            data: {
                ajax: 1,
                cerca: cerca
            },
            success: function(response) {
                if (!Array.isArray(response)) { $('#baEntrate, #baUscite, #baSaldo').text('Dati non disponibili'); return; }
                creaGrafico(response);
                ResiduiMensiliGrafico(response);
                aggiornaRiepilogo(response);
                aggiornaCategorie();
            },
            error: function() { $('#baEntrate, #baUscite, #baSaldo').text('Dati non disponibili'); }
        });

    }

    let categoryRequest = null;
    function aggiornaCategorie() {
        if (categoryRequest) categoryRequest.abort();
        const container = $('#baTopCategories').text('Caricamento categorie...');
        categoryRequest = $.ajax({
            type: 'POST', dataType: 'json',
            url: './php/json/preleva_dati_categorie_top.php',
            data: {cerca: $('#anno').val() || ''}
        }).done(function (rows) {
            container.empty();
            if (!Array.isArray(rows) || !rows.length) {
                container.text('Nessuna spesa nel periodo selezionato.');
                return;
            }
            const total = rows.reduce((sum, row) => sum + (Number(row.totale) || 0), 0);
            const euro = new Intl.NumberFormat('it-IT', {style:'currency',currency:'EUR'});
            const list = $('<div class="ba-category-ranking"></div>');
            rows.forEach(row => {
                const amount = Number(row.totale) || 0;
                const item = $('<div class="ba-category-rank-item"></div>');
                const header = $('<div class="ba-category-rank-label"></div>');
                $('<span></span>').text(row.categoria || 'Senza categoria').appendTo(header);
                $('<strong></strong>').text(euro.format(amount)).appendTo(header);
                item.append(header);
                $('<div class="ba-category-rank-track"></div>').append(
                    $('<div class="ba-category-rank-fill"></div>').css('width', total ? (100 * amount / total) + '%' : '0%')
                ).appendTo(item);
                list.append(item);
            });
            container.append(list);
        }).fail(function (_xhr, status) {
            if (status !== 'abort') container.text('Impossibile caricare le categorie.');
        });
    }

    function aggiornaRiepilogo(rows) {
        if (!Array.isArray(rows)) throw new Error('Formato report non valido');
        const entrate = rows.reduce((sum, row) => sum + (Number(row.entrate) || 0), 0);
        const uscite = rows.reduce((sum, row) => sum + Math.abs(Number(row.uscite) || 0), 0);
        const euro = new Intl.NumberFormat('it-IT', {style:'currency', currency:'EUR'});
        $('#baEntrate').text(euro.format(entrate));
        $('#baUscite').text(euro.format(uscite));
        $('#baSaldo').text(euro.format(entrate - uscite));
        const latest = rows[rows.length - 1];
        const previous = rows.length > 1 ? rows[rows.length - 2] : null;
        $('#baLatestBalance').text(latest ? euro.format((Number(latest.entrate)||0) - Math.abs(Number(latest.uscite)||0)) : '—');
        $('#baLatestMonth').text(latest ? latest.mese : 'Nessun dato disponibile');
        if (latest && previous) {
            const currentExpense = Math.abs(Number(latest.uscite)||0);
            const priorExpense = Math.abs(Number(previous.uscite)||0);
            const change = currentExpense - priorExpense;
            $('#baExpenseChange').text((change > 0 ? '+' : '') + euro.format(change))
                .toggleClass('ba-amount-negative', change > 0).toggleClass('ba-amount-positive', change < 0);
            $('#baExpensePeriod').text(String(previous.mese) + ' → ' + String(latest.mese));
        } else {
            $('#baExpenseChange').text('—').removeClass('ba-amount-negative ba-amount-positive');
            $('#baExpensePeriod').text('Servono almeno due mesi con dati');
        }
        const container = $('#resmensile').empty();
        if (!rows.length) {
            container.text('Nessuna transazione per il periodo selezionato.');
            return;
        }
        const table = $('<table class="table table-hover align-middle mb-0"></table>');
        table.append('<thead><tr><th>Mese</th><th class="text-end">Entrate</th><th class="text-end">Uscite</th><th class="text-end">Saldo</th></tr></thead>');
        const tbody = $('<tbody></tbody>');
        rows.forEach(row => {
            const income = Number(row.entrate) || 0;
            const expense = Math.abs(Number(row.uscite) || 0);
            const tr = $('<tr></tr>');
            $('<td></td>').text(row.mese ?? '').appendTo(tr);
            $('<td class="text-end"></td>').text(euro.format(income)).appendTo(tr);
            $('<td class="text-end"></td>').text(euro.format(expense)).appendTo(tr);
            $('<td class="text-end fw-semibold"></td>').addClass(income-expense >= 0 ? 'text-success' : 'text-danger').text(euro.format(income-expense)).appendTo(tr);
            tbody.append(tr);
        });
        table.append(tbody);
        container.append($('<div class="table-responsive"></div>').append(table));
    }

    // Grafico entrate e uscite
    function creaGrafico(label) {

        var mesi = label.map(function(e) {
            return e.mese;
        });
        var entrate = label.map(function(e) {
            return Number(e.entrate) || 0;
        });
        var uscite = label.map(function(e) {
            return Math.abs(Number(e.uscite) || 0);
        });

        //Controllo se è gia presente un Grafico
        let chartStatus = Chart.getChart("SpeseChart"); // <canvas> id
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        //Dopo aver eliminato il vecchio grafico, genero il secondo
        const ctx = document.getElementById('SpeseChart');
        const report = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: mesi,
                datasets: [{
                    // values for category one
                    type: 'bar',
                    label: "Entrate",
                    backgroundColor: '#20b486',
                    borderWidth:0,
                    borderRadius:20,
                    borderSkipped:false,
                    data: entrate,
                    //stack: 'Stack 1'
                }, {
                    // values for category two
                    type: 'bar',
                    label: "Uscite",
                    backgroundColor: '#f07883',
                    borderWidth:0,
                    borderRadius:20,
                    borderSkipped:false,
                    data: uscite,
                    //stack: 'Stack 1'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Grafico residuo mensile: contrasto adattivo e tooltip in euro.
    function ResiduiMensiliGrafico(rows) {
        const mesi = rows.map(row => row.mese);
        const saldi = rows.map(row => Number(row.ris) || 0);
        const old = Chart.getChart('ResiduoChart');
        if (old) old.destroy();
        const dark = document.documentElement.getAttribute('data-ba-theme') === 'dark';
        const ink = dark ? '#c6d4e9' : '#60718b';
        const grid = dark ? 'rgba(180,198,227,.12)' : 'rgba(78,101,139,.10)';
        const euro = new Intl.NumberFormat('it-IT', {style:'currency',currency:'EUR'});
        const canvas = document.getElementById('ResiduoChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const fill = ctx.createLinearGradient(0,0,0,360);
        fill.addColorStop(0, dark ? 'rgba(112,145,255,.23)' : 'rgba(82,115,236,.19)');
        fill.addColorStop(1,'rgba(82,115,236,0)');
        new Chart(ctx, {
            type:'line',
            data:{labels:mesi,datasets:[{
                label:'Residuo mensile',data:saldi,
                borderColor:'#7191ff',backgroundColor:fill,
                pointBackgroundColor:saldi.map(v => v < 0 ? '#f07883' : '#7191ff'),
                pointBorderColor:dark?'#192538':'#ffffff',
                pointBorderWidth:2,pointRadius:3,pointHoverRadius:7,
                pointHitRadius:18,borderWidth:3,tension:.3,fill:'origin'
            }]},
            options:{
                responsive:true,maintainAspectRatio:false,
                interaction:{mode:'index',intersect:false},
                plugins:{
                    legend:{display:false},
                    tooltip:{
                        enabled:true,displayColors:false,
                        backgroundColor:dark?'#101827':'#18253a',
                        titleColor:'#ffffff',bodyColor:'#ffffff',
                        padding:12,cornerRadius:10,
                        callbacks:{label:context => 'Residuo: '+euro.format(context.parsed.y)}
                    }
                },
                scales:{
                    x:{grid:{display:false},border:{display:false},
                       ticks:{color:ink,maxRotation:0,minRotation:0,autoSkip:true,maxTicksLimit:6,font:{size:11}}},
                    y:{beginAtZero:false,grid:{color:grid},border:{display:false},
                       ticks:{color:ink,maxTicksLimit:6,callback:value => new Intl.NumberFormat('it-IT',{notation:'compact',maximumFractionDigits:1}).format(value)+' €'}}
                }
            }
        });
    }

    function ReminderTransazioni(){
    
    }
</script>

</html>