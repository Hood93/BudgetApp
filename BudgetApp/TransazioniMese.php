<!DOCTYPE html>
<html lang="en">

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
    <title>Transazioni Totali</title>
</head>

<body>
    <div class="container-fluid">
        <?php
        include "gvariable.php"; // File Variabili
        include "componenti/navbar.php"; // File Navbar
        include "php/utility.php";

        ?>
        <div class="container-fluid" id="transazionitotalimese" style="padding-top: 90px !important;">
        </div>
    </div>

    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <!-- End of Jquery CDN -->
    <!-- Script funzioni -->
    <script>
        $(document).ready(function() {
            preleva_dati();

            //Funzione Display Modal Insert
            $('#add').click(function() {
                $('#ModalNewOperazione').modal('show');
            });

            //Funzione Prelievo dati
            function preleva_dati() {
                var anno = new Date();
                var mese = new Date();
                mese = mese.getMonth();
                anno = anno.getFullYear();
                $.ajax({
                    type: 'POST',
                    url: './php/preleva_dati_budget_mese.php',
                    data: {
                        ajax: 1,
                        anno:anno,
                        mese:mese
                    },
                    success: function(response) {
                        $("#transazionitotalimese").html(response);
                    }
                });
            }
        });



        //Funzione Ricerca Transazioni
        function cercacontrollo(_this) {
            var cerca = $(_this).val();
            console.log(cerca);
            $.ajax({
                type: 'POST',
                url: './php/preleva_dati_budget.php',
                data: {
                    ajax: 1,
                    cerca: cerca
                },
                success: function(response) {
                    $("#transazionitotali").html(response);
                }
            });
        };

        //Funzione Ricerca per anno
        function filtraanno(_this) {
            var anno = $(_this).val();
            var mese = document.getElementById('filtromese');
            var tag1 = document.getElementById('filtrotag1');
            var categoria = document.getElementById('filtrocategoria');
            mese = mese.value;
            categoria = categoria.value;
            tag1 = tag1.value;
            $.ajax({
                type: 'POST',
                url: './php/preleva_dati_budget.php',
                data: {
                    ajax: 1,
                    anno: anno,
                    mese: mese,
                    tag1:tag1,
                    categoria: categoria
                },
                success: function(response) {
                    $("#transazionitotali").html(response);
                }
            });
        };

        //Funzione Ricerca per Mese
        function filtramese(_this) {
            var mese = $(_this).val();
            var anno = document.getElementById('filtroanno');
            var tag1 = document.getElementById('filtrotag1');
            var categoria = document.getElementById('filtrocategoria');
            anno = anno.value;
            categoria = categoria.value;
            tag1 = tag1.value;
            $.ajax({
                type: 'POST',
                url: './php/preleva_dati_budget.php',
                data: {
                    ajax: 1,
                    mese: mese,
                    anno: anno,
                    tag1:tag1,
                    categoria: categoria
                },
                success: function(response) {
                    $("#transazionitotali").html(response);
                }
            });
        };

        //Funzione Ricerca per Categoria
        function filtracategoria(_this) {
            var categoria = $(_this).val();
            var anno = document.getElementById('filtroanno');
            var mese = document.getElementById('filtromese');
            var tag1 = document.getElementById('filtrotag1');
            anno = anno.value;
            mese = mese.value;
            tag1 = tag1.value;
            $.ajax({
                type: 'POST',
                url: './php/preleva_dati_budget.php',
                data: {
                    ajax:1,
                    categoria:categoria,
                    anno:anno,
                    mese:mese,
                    tag1:tag1
                },
                success: function(response){
                    $('#transazionitotali').html(response);
                }
            });
        };

        //Funzione Ricerca Tag1
        function filtratag1(_this) {
            var tag1 = $(_this).val();
            var anno = document.getElementById('filtroanno');
            var mese = document.getElementById('filtromese');
            var categoria = document.getElementById('filtrocategoria');
            anno = anno.value;
            mese = mese.value;
            categoria = categoria.value;
            $.ajax({
                type: 'POST',
                url: './php/preleva_dati_budget.php',
                data: {
                    ajax:1,
                    tag1:tag1,
                    anno:anno,
                    mese:mese,
                    categoria:categoria
                },
                success: function(response){
                    $('#transazionitotali').html(response);
                }
            });
        };
    </script>

    <!-- End of Script Funzioni -->
</body>

</html>