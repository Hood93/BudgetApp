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
    <title>Transazioni Pending</title>
</head>

<body>
    <div class="container-fluid">
        <?php
        include "gvariable.php"; // File Variabili
        include "componenti/navbar.php"; // File Navbar
        include "php/utility.php"; //File Utility

        ?>
        <div class="container-fluid">
            <?php include "componenti/Modal/ModalEditTransazioniPending.php"; // File Modal Edit 
            ?>
        </div>
        <section class="ba-welcome"><div class="ba-eyebrow">DA VERIFICARE</div><h2>Transazioni in sospeso</h2><p>Filtra i movimenti e abilita la modifica per le operazioni in blocco.</p></section>
        <section class="ba-panel ba-filter-panel mb-4">
          <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-3">
            <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="ablemod" onclick="AbilitaModifica(this)"><label class="form-check-label fw-semibold" for="ablemod">Abilita modifica in blocco</label></div>
            <div class="d-flex flex-wrap gap-2">
              <button type="button" class="btn btn-primary savebtn" id="blocksave" disabled><i class="fa-solid fa-check me-1"></i> Salva</button>
              <button type="button" class="btn btn-outline-danger delbtn" id="blockdel" disabled><i class="fa-solid fa-trash me-1"></i> Elimina</button>
              <button type="button" class="btn btn-outline-secondary checkallbtn" id="checkall" disabled><i class="fa-solid fa-check-double me-1"></i> Seleziona tutto</button>
            </div>
          </div>
          <div class="row"><div class="col-12 col-md-5"><label class="form-label" for="filtrcategoria">Filtra per categoria</label><select class="form-select" name="filtrcategoria" id="filtrcategoria" onchange="filtracategoria(this)"><option value="">Tutte le categorie</option><?php foreach ($catarray as $cat): ?><option value="<?php echo htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8'); ?></option><?php endforeach; ?></select></div></div>
        </section>
        <div class="ba-results-panel" id="transazionipending" aria-live="polite">
        </div>
    </div>

    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <!-- End of Jquery CDN -->
    <!-- Script funzioni -->
    <script>
        var arraytranpendmod = [];
        console.log(arraytranpendmod)
        $(document).ready(function() {
            preleva_dati();

            //Funzione Display Modal Insert
            $('#add').click(function() {
                $('#ModalEditTransazioniPending').modal('show');
            });

            //Funzione Prelievo dati
            function preleva_dati() {
                $.ajax({
                    type: 'POST',
                    url: './php/preleva_dati_transazioni_pending.php',
                    data: {
                        ajax: 1,
                    },
                    success: function(response) {
                        $("#transazionipending").html(response);
                    }
                });
            }
        });


        $(".savebtn").on('click', function() {
            console.log(arraytranpendmod)
            var operazione = "inserimento";
            $.ajax({
                type: 'POST',
                url: 'php/updtransazionipendingbloc.php',
                data: {
                    ajax: 1,
                    arraytranpendmod: arraytranpendmod,
                    operazione: operazione
                },
                success: function(response) {
                    location.reload();
                }


            });



        })

        //Funzione Seleziona Tutto
        $(".checkallbtn").on('click',function(){
            var tabella = document.getElementById("table_TransazioniTotali")
            var checkboxtot = tabella.querySelectorAll('input[type=checkbox]')
            for (let i = 0; i < checkboxtot.length; i++) {
                    checkboxtot[i].checked = true;
                    var elementi = checkboxtot[i].value;
                    arraytranpendmod.push(elementi) 
                }
        })

        // End of Seleziona Tutto

        //Funzione Filtro Categoria
        function filtracategoria(_this) {
            var categoria = $(_this).val();
            $.ajax({
                type: 'POST',
                url: './php/preleva_dati_transazioni_pending.php',
                data: {
                    ajax:1,
                    categoria:categoria
                },
                success: function(response){
                    $('#transazionipending').html(response);
                }
            });
        };

        // End Of Funzione Filtro Categoria


        // Tasto per eliminare in blocco le transazioni
        $('.delbtn').on('click', function() {
            //console.log(arraytranpendmod)
            var operazione = "elimina"
            var conferma = confirm("Sei sicuro di voler eliminare questa transazione");
            if (conferma == true) {
                $.ajax({
                    type: 'POST',
                    url: 'php/updtransazionipendingbloc.php',
                    data: {
                        ajax: 1,
                        arraytranpendmod: arraytranpendmod,
                        operazione: operazione

                    },
                    success: function(response) {
                        location.reload();
                    }


                });
            } else {
                alert("Transazione non eliminata")
            }
        })
        // End of Tasto per eliminare in blocco le transazioni

        //Funzione per abilitare la modifica in blocco
        function AbilitaModifica(_this) {
            var check = _this.checked
            if (check == true) {
                $("#blocksave").prop("disabled", false);
                $("#blockdel").prop("disabled", false);
                $("#checkall").prop("disabled", false);
                var tabella = document.getElementById("table_TransazioniTotali")
                var checkboxtot = tabella.querySelectorAll('input[type=checkbox]')
                var buttontot = tabella.querySelectorAll('[type=button]')
                for (let i = 0; i < checkboxtot.length; i++) {
                    checkboxtot[i].disabled = false;
                }
                for (let i = 0; i < buttontot.length; i++) {
                    buttontot[i].disabled = true;
                }
            } else {
                $("#blocksave").attr('disabled', 'disabled');
                $("#blockdel").attr('disabled', 'disabled');
                $("#checkall").prop("disabled", 'disabled');
                var tabella = document.getElementById("table_TransazioniTotali")
                var checkboxtot = tabella.querySelectorAll('input[type=checkbox]')
                var buttontot = tabella.querySelectorAll('[type=button]')
                for (let i = 0; i < checkboxtot.length; i++) {
                    checkboxtot[i].disabled = true;
                    checkboxtot[i].checked = false
                }
                for (let i = 0; i < buttontot.length; i++) {
                    buttontot[i].disabled = false;
                }
            }
        }
        // End Of Funzione per abilitare la modifica in blocco
    </script>

    <!-- End of Script Funzioni -->
</body>

</html>