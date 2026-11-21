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
        $('#anno').val(String(new Date().getFullYear()));
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
                    cerca: $('#anno').val() || String(new Date().getFullYear()),
                },

                success: function(response) {
                    creaGrafico(response)
                    ResiduiMensiliGrafico(response);
                    aggiornaRiepilogo(response);
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
                creaGrafico(response);
                ResiduiMensiliGrafico(response);
                aggiornaRiepilogo(response);
            },
            error: function() { $('#baEntrate, #baUscite, #baSaldo').text('Dati non disponibili'); }
        });

    }

    function aggiornaRiepilogo(rows) {\n        if (!Array.isArray(rows)) return;\n        const parseAmount = value => {\n            if (typeof value === "number") return Number.isFinite(value) ? value : 0;\n            let v = String(value ?? "0").replace(/[^0-9,.-]/g, "");\n            if (v.includes(",") && v.includes(".")) v = v.replace(/\\./g, "").replace(",", ".");\n            else if (v.includes(",")) v = v.replace(",", ".");\n            return Number(v) || 0;\n        };\n        const entrate = rows.reduce((sum, row) => sum + parseAmount((row.entrate ?? row.Entrate)), 0);\n        const uscite = rows.reduce((sum, row) => sum + Math.abs(parseAmount((row.uscite ?? row.Uscite))), 0);\n        const euro = new Intl.NumberFormat("it-IT", {style:"currency",currency:"EUR"});\n        $("#baEntrate").text(euro.format(entrate));\n        $("#baUscite").text(euro.format(uscite));\n        $("#baSaldo").text(euro.format(entrate - uscite));\n    }\n\n    //Funzione Grafico\n    function creaGrafico(label) {

        var mesi = label.map(function(e) {
            return e.mese;
        });
        var entrate = label.map(function(e) {
            return Number(e.entrate) || 0;
        });
        var uscite = label.map(function(e) {
            return (e.uscite * -1);
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

    //Funzione Grafico Residui Mensili

    function ResiduiMensiliGrafico(label) {
        var mesi = label.map(function(e) {
            return e.mese;
        });
        var entrate = label.map(function(e) {
            return Number(e.entrate) || 0;
        });
        var uscite = label.map(function(e) {
            return Number(e.uscite) || 0;
        });
        var ris = label.map(function(e) {
            return Number(e.ris) || 0;
        });

        //Controllo se è gia presente un Grafico
        let chartStatus = Chart.getChart("ResiduoChart"); // <canvas> id
        if (chartStatus != undefined) {
            chartStatus.destroy();
        }

        const ctx = document.getElementById('ResiduoChart');
        //Dopo aver eliminato il vecchio grafico, genero il secondo
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: mesi,
                datasets: [{
                    label: 'Residuo Mensile',
                    data: ris,
                    pointRadius:4,
                    pointHoverRadius:7,
                    borderColor: '#5273ec',
                    backgroundColor: 'rgba(82,115,236,.10)',
                    tension: .35,
                    borderWidth: 3,
                    fill:true
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

    function ReminderTransazioni(){
    
    }
</script>

</html>