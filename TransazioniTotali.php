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
            <section class="ba-panel ba-filter-panel mb-4">
              <div class="row g-3 align-items-end">
                <div class="col-12 col-lg-8"><label class="form-label" for="baSearch">Ricerca transazioni</label><div class="input-group"><span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span><input id="baSearch" type="search" class="form-control" onkeyup="cercacontrollo(this)" placeholder="Cerca una transazione..."></div></div>
                <div class="col-12 col-lg-4"><button class="btn btn-primary w-100" name="add" id="add"><i class="fa-solid fa-plus me-2"></i>Aggiungi transazione</button></div>
                <div class="col-6 col-lg-3"><label class="form-label" for="filtroanno">Anno</label><select class="form-select" name="filtroanno" id="filtroanno" onchange="filtraanno(this)"><option value="">Tutti</option><?php foreach ($tableanno as $anno): ?><option value="<?php echo htmlspecialchars((string)$anno, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$anno, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?></select></div>
                <div class="col-6 col-lg-3"><label class="form-label" for="filtromese">Mese</label><select class="form-select" name="filtromese" id="filtromese" onchange="filtramese(this)"><option value="">Tutti</option><?php foreach ($mesi as $mese): ?><option value="<?php echo htmlspecialchars((string)$mese, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$mese, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?></select></div>
                <div class="col-6 col-lg-3"><label class="form-label" for="filtrotag1">Tag</label><input type="text" name="filtrotag1" id="filtrotag1" class="form-control" onkeyup="filtratag1(this)" placeholder="Filtra per tag"></div>
                <div class="col-6 col-lg-3"><label class="form-label" for="filtrocategoria">Categoria</label><select class="form-select" name="filtrocategoria" id="filtrocategoria" onchange="filtracategoria(this)"><option value="">Tutte</option><?php foreach ($catarray as $categoria): ?><option value="<?php echo htmlspecialchars((string)$categoria, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$categoria, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?></select></div>
              </div>
            </section>
        </div>
        <div class="ba-results-panel" id="transazionitotali" aria-live="polite">
        </div>
    </main>

    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <!-- End of Jquery CDN -->
    <!-- Script funzioni -->
    <script>
        // One request pipeline for all filters: debounce typing, cancel stale requests.
        (function () {
            let request = null;
            let debounceTimer = null;
            let requestSequence = 0;
            const filters = () => ({
                ajax: 1,
                cerca: $('#baSearch').val() || '',
                anno: $('#filtroanno').val() || '',
                mese: $('#filtromese').val() || '',
                tag1: $('#filtrotag1').val() || '',
                categoria: $('#filtrocategoria').val() || ''
            });
            function refresh() {
                clearTimeout(debounceTimer);
                const sequence = ++requestSequence;
                if (request) request.abort();
                $('#transazionitotali').attr('aria-busy', 'true');
                request = $.ajax({
                    type: 'POST',
                    url: './php/preleva_dati_budget.php',
                    data: filters()
                }).done(function (html) {
                    if (sequence === requestSequence) $('#transazionitotali').html(html);
                }).fail(function (xhr, status) {
                    if (status !== 'abort' && sequence === requestSequence) {
                        $('#transazionitotali').html('<p class="ba-empty-state">Impossibile caricare le transazioni. Riprova.</p>');
                    }
                }).always(function () {
                    if (sequence === requestSequence) $('#transazionitotali').removeAttr('aria-busy');
                });
            }
            function schedule() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(refresh, 250);
            }
            window.cercacontrollo = schedule;
            window.filtratag1 = schedule;
            window.filtraanno = refresh;
            window.filtramese = refresh;
            window.filtracategoria = refresh;
            $(function () {
                refresh();
                $('#add').on('click', function () { $('#ModalNewOperazione').modal('show'); });
            });
        })();
    </script>

    <!-- End of Script Funzioni -->
</body>

</html>