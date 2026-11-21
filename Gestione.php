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
    <!-- CSS -->
    <link rel="stylesheet" href="css/card.css">
    <link rel="stylesheet" href="css/transricorenti.css">
    <!-- <link rel="stylesheet" href="css/layout.css">
    CSS -->
    <title>Gestione</title>
</head>

<body>
    <?php
    include "gvariable.php"; // File Variabili
    include "php/utility.php"; // File Utility
    include "componenti/navbar.php"; // File Navbar
    include "componenti/Modal/ModalEditCategoria.php"; //File Modal Categoria;
    include "componenti/Modal/ModalInsertCategoria.php"; //File Modal Categoria;
    include "componenti/Modal/ModalInsertOperazioneRic.php"; //File Modal Categoria;
    include "componenti/Modal/ModalViewTransazioniRicorrenti.php"; //File Modal TransazioniRicorrenti;
    include "componenti/Modal/ModalViewDettaglioCategorieMese.php"; //File Modal DettagliCategorieMese;
    include "componenti/Modal/ModalInsertAmex.php"; //File Modal Inserimento Dettaglio Amex
    include "componenti/Modal/ModalFormFile.php"; //File Modal Upload File
    include "componenti/Modal/ModalTabelleAssociative.php"; //File Modal Dettaglio Tabelle Associative
    include "componenti/Modal/ModalInsertCreditore.php"; //File Modal Inserimento Dettaglio Amex
    include "componenti/Modal/ModalEditDescrizioneAssociativa.php"; //File Modal Edit Des Associativa
    include "componenti/Modal/ModalInsertDescrizioneAssociativa.php"; //File Modal Inserimento Des Associativa
    include "componenti/Modal/ModalEditCategoriaAssociativa.php"; //File Modal Edit Cat Associativa
    include "componenti/Modal/ModalInsertCategoriaAssociativa.php"; //File Modal Inserimento Cat Associativa
    

    ?>
    <main class="container ba-management">
        <section class="ba-welcome"><div class="ba-eyebrow">CENTRO DI CONTROLLO</div><h2>Gestione del budget</h2><p>Amministra categorie, operazioni ricorrenti, crediti e importazioni.</p></section>
        <section class="ba-panel ba-management-toolbar mb-4"><div class="row g-3 align-items-stretch" style="padding-top: 12px !important; padding-bottom: 12px !important;">
            <div class="col-12 col-md-6 col-xl-3 ba-management-year">
                <label class="form-label" for="anno">Selezione Anno Report</label>
                <?php echo "<select class='form-select' onchange='selanno(this)' name='anno' id='anno'>";
                echo "<option value=''></option>";
                foreach ($tableanno as $anno) {
                    echo "<option value='" . $anno . "'>" . $anno . "</option>";
                }
                echo "</select>"; ?>
            </div>
            <div class="col-12 col-xl-9 ba-management-month" id="resmensile">
            </div>
            <div class="col-12 col-md-4 ba-management-actions">
                <div class="d-grid gap-2">
                    <button class="btn btn-dark py-2 btn-sm text-start" name="addtranric" id="addtranric"><i class="fa-solid fa-circle-plus fa-sm" style="color: #77bb41;"></i>
                        Aggiungi Transazione Ricorrente
                    </button>
                    <button class="btn btn-dark py-2 btn-sm text-start insnuovoanno" name="insnuovoanno" id="insnuovoanno"><i class="fa-solid fa-circle-plus fa-sm" style="color: #77bb41;"></i>
                        Inserisci Nuovo Anno
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-4 ba-management-actions">
                <div class="d-grid gap-2">
                    <button class="btn btn-dark py-2 btn-sm text-start" name="addcat" id="addcat"><i class="fa-solid fa-circle-plus fa-sm" style="color: #77bb41;"></i>
                        Aggiungi Categoria
                    </button>
                    <button class="btn btn-dark py-2 btn-sm text-start uploadfile" name="uploadfile" id="uploadfile"><i class="fa-solid fa-circle-plus fa-sm" style="color: #77bb41;"></i>
                        Carica File Transazioni
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-4 ba-management-actions">
                <div class="d-grid gap-2">
                    <button class="btn btn-dark py-2 btn-sm text-start" name="addcredito" id="addcredito"><i class="fa-solid fa-circle-plus fa-sm" style="color: #77bb41;"></i>
                        Aggiungi Credito
                    </button>
                    <button class="btn btn-dark py-2 btn-sm text-start" name="tabelleassociative" id="tabelleassociative"><i class="fa-solid fa-circle-plus fa-sm" style="color: #77bb41;"></i>
                        Visualizza Tabelle Associative
                    </button>
                </div>
            </div>
        </div>
        </section>
        <div class="row g-4 ba-management-reports">
            <div class="col-12 col-xl-8" id="resannuale">
            </div>

            <div class="col-12 col-xl-4" id="categorie">
            </div>
        </div>
        <div class="row">
            <div class="col-xxl" id="restransazioniricorrenti">
            </div>
        </div>
        <div class="row">
            <div class="col-xxl" id="resannualecategorie">
            </div>
        </div>
        <div class="row">
            <div class="col-xxl" id="rescrediti">
            </div>
        </div>
    </main>
    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <!-- End of Jquery CDN -->
    <!-- CDN Icone-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDN Icone-->
</body>

<script>
    $(document).ready(function() {
        getcategorie();
        getresocontoannuale();
        getresocontoannualecategorie();
        getresocontomensile();
        getresocontotranricorrenti();
        getcrediti();
        gettabassdescrizione();
        gettabasscategoria();

        //Funzione Upload
        $('#uploadfile').click(function() {
            $('#ModalFile').modal('show')

        })


        //Funzione Inserimento Nuovo anno
        $('#insnuovoanno').click(function() {
            var conferma = confirm("Sei sicuro di voler Inserire un nuovo anno?");
            if (conferma == true) {
                $.ajax({
                    type: 'POST',
                    url: './php/insnuovoanno.php',
                    data: {
                        ajax: 1,
                    },
                    success: function(response) {
                        alert('Nuovo Anno Inserito');
                    }
                });
            }
        })

        //Funzione Display Modal Insert Categoria
        $('#addcat').click(function() {
            console.log("categoria")
            $('#ModalNewCategoria').modal('show');
        });

        //Funzione Display Modal Insert Trans Ric
        $('#addtranric').click(function() {
            $('#ModalNewOperazioneRic').modal('show');
        });

       $('#addcredito').click(function() {
            $('#ModalInsertCreditore').modal('show');
        });

        //Visualizza Tabelle Associative
        $('#tabelleassociative').click(function() {
            $('#ModalTabAssociative').modal('show');
        });

        //Prelevo Categorie
        function getcategorie() {

            $.ajax({
                type: 'POST',
                url: './php/preleva_dati_categorie.php',
                data: {
                    ajax: 1,
                },
                success: function(response) {
                    $("#categorie").html(response);
                }
            });

        }

    })


    //Prelevo Resoconto Annuale
    function getresocontoannuale() {
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_reportannuale.php',
            data: {
                ajax: 1,

            },
            success: function(response) {
                $("#resannuale").html(response);
            }
        });

    }

    function getresocontotranricorrenti() {
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_transricorrenti.php',
            data: {
                ajax: 1,

            },
            success: function(response) {
                $("#restransazioniricorrenti").html(response);
            }
        });

    }

    // Prelevo Resoconto Mensile
    function getresocontomensile() {
        var mese = new Date();
        var anno = new Date();
        mese = mese.getMonth();
        anno = anno.getFullYear();
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_reportmese.php',
            data: {
                ajax: 1,
                mese: mese,
                anno: anno

            },
            success: function(response) {
                $("#resmensile").html(response);
            }
        });

    }


    //Prelevo Resoconto Annuale per categorie
    function getresocontoannualecategorie() {
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_reportannualecategorie.php',
            data: {
                ajax: 1,

            },
            success: function(response) {
                $("#resannualecategorie").html(response);
            }
        });

    }

    //Prelevo Resoconto Annuale per anno

    function selanno(_this) {
        var cerca = $(_this).val();
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_reportannuale.php',
            data: {
                ajax: 1,
                cerca: cerca
            },
            success: function(response) {
                $("#resannuale").html(response);
            }
        });
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_reportannualecategorie.php',
            data: {
                ajax: 1,
                cerca: cerca
            },
            success: function(response) {
                $("#resannualecategorie").html(response);
            }
        });
    }

    //prelevo crediti

    function getcrediti() {
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_crediti.php',
            data: {
                ajax: 1,

            },
            success: function(response) {
                $("#rescrediti").html(response);
            }
        });
    }


    function apriCollapse(_this) {
        var idcreditore = $(_this).attr("idcreditore");
        var $panel = $('#showdettagliocredito');
        var $button = $(_this);
        if ($panel.hasClass('show') && $panel.attr('data-creditore') === idcreditore) {
            $panel.collapse('hide');
            $button.attr('aria-expanded', 'false');
            return;
        }
        $('.ba-credit-detail').attr('aria-expanded', 'false');
        $button.attr('aria-expanded', 'true');
        $panel.attr('data-creditore', idcreditore).html('<div class="ba-credit-loading">Caricamento rate...</div>');
        $panel.collapse('show');
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_dettagliocreditore.php',
            data: { ajax: 1, idcreditore: idcreditore },
            success: function(response) {
                if ($panel.attr('data-creditore') === idcreditore) $panel.html(response);
            },
            error: function() {
                if ($panel.attr('data-creditore') === idcreditore) $panel.text('Impossibile caricare le rate.');
            }
        });
    }

    function gettabassdescrizione() {
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_descassociative.php',
            data: {
                ajax: 1,

            },
            success: function(response) {
                $("#tabassdescrizione").html(response);
            }
        });

    }

    function gettabasscategoria() {
        $.ajax({
            type: 'POST',
            url: './php/preleva_dati_catassociative.php',
            data: {
                ajax: 1,

            },
            success: function(response) {
                $("#tabasscategoria").html(response);
            }
        });

    }
</script>



</html>