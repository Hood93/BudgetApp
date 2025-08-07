<?php

$dirpath = (__DIR__);
$nfile = (__FILE__);
$dirpath = str_replace("/var/www/html/BudgetApp","",$dirpath);
$nfile = str_replace("/var/www/html/BudgetApp/","","$nfile");
$nfile = str_replace(".php","",$nfile);
$GLOBALS['Homepage'] = $dirpath . "Homepage.php";
$GLOBALS['Transazioni'] = $dirpath . "TransazioniTotali.php";
$GLOBALS['TransazioniMese'] = $dirpath . "TransazioniMese.php";
$GLOBALS['TransazioniPending'] = $dirpath . "TransazioniPending.php";
//$GLOBALS['Reportannuale'] = $dirpath . "ReportAnnualeBudget.php";
//$GLOBALS['Reportannualecategorie'] = $dirpath . "ReportAnnualeBudgetCategorie.php";
$GLOBALS['Gestione'] = $dirpath . "Gestione.php";
$GLOBALS['File'] = $nfile;
?>