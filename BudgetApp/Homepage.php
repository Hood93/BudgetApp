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
    <!-- CDN Icone-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDN Icone -->
    <link rel="stylesheet" href="/css/graph.css">
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
        <div class="row mt-5">
            <div class="container-fluid">
                <div class="row" style="padding-top: 20px !important; padding-bottom: 20px !important;">
                    <div class="col">
                        Selezione Anno Report
                        <?php echo "<select class='form-select' onchange='selanno(this)' name='anno' id='anno'>";
                        echo "<option value=''></option>";
                        foreach ($tableanno as $anno) {
                            echo "<option value='" . $anno . "'>" . $anno . "</option>";
                        }
                        echo "</select>"; ?>
                    </div>
                    <div class="col" id="resmensile">
                        Mo vediamo
                    </div>
                </div>
                <div>
                    <canvas id="SpeseChart" style="max-height: 300px;"></canvas>
                </div>
                <div>
                    <canvas id="ResiduoChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


            </div>
        </div>
    </div>
    <!-- Container Principale -->
    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <!-- End of Jquery CDN -->
</body>
<script>
    $(document).ready(function() {
        getresocontoannuale()
        ReminderTransazioni()



        //Prelevo Resoconto Annuale
        function getresocontoannuale() {
            $.ajax({

                type: 'GET',
                url: './php/json/preleva_dati_reportannualejson.php',
                dataType: 'JSON',
                data: {
                    ajax: 1,
                },

                success: function(response) {
                    creaGrafico(response)
                    ResiduiMensiliGrafico(response);
                }
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
            }
        });

    }

    //Funzione Grafico
    function creaGrafico(label) {

        var mesi = label.map(function(e) {
            return e.mese;
        });
        var entrate = label.map(function(e) {
            return e.entrate;
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
                    backgroundColor: '#119f19',
                    borderWidth:0,
                    borderRadius:20,
                    borderSkipped:false,
                    data: entrate,
                    //stack: 'Stack 1'
                }, {
                    // values for category two
                    type: 'bar',
                    label: "Uscite",
                    backgroundColor: '#e3201b',
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
            return e.entrate;
        });
        var uscite = label.map(function(e) {
            return e.uscite;
        });
        var ris = label.map(function(e) {
            return e.ris;
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
                    pointRadius:10,
                    pointHoverRadius:15,
                    borderWidth: 1,
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