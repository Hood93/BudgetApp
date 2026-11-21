<?php
require_once __DIR__ . '/db.php';

// Shared lookup data used by forms, reports and CSV imports.
$mesi = [
    1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo', 4 => 'Aprile',
    5 => 'Maggio', 6 => 'Giugno', 7 => 'Luglio', 8 => 'Agosto',
    9 => 'Settembre', 10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre',
];
$mesi0 = [];
foreach ($mesi as $number => $name) {
    $mesi0[sprintf('%02d', $number)] = $name;
}
$catarray = [];
$tableanno = [];
$descrizionearr = [];
$catdescarr = [];

$lookups = [
    ['SELECT categoria FROM categoriebudget ORDER BY categoria ASC', 'catarray', 'categoria', null],
    ['SELECT DISTINCT anno FROM budgettable ORDER BY anno ASC', 'tableanno', 'anno', null],
    ['SELECT olddescrizione, nuovadescrizione FROM descrizioneassociativa', 'descrizionearr', 'nuovadescrizione', 'olddescrizione'],
    ['SELECT descrizione, categoria FROM categoriaassociativa', 'catdescarr', 'categoria', 'descrizione'],
];
foreach ($lookups as [$sql, $target, $valueColumn, $keyColumn]) {
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        error_log('BudgetApp lookup failed: ' . mysqli_error($conn));
        http_response_code(500);
        exit('Impossibile caricare i dati di riferimento');
    }
    while ($row = mysqli_fetch_assoc($result)) {
        if ($keyColumn === null) {
            ${$target}[] = $row[$valueColumn];
        } else {
            ${$target}[$row[$keyColumn]] = $row[$valueColumn];
        }
    }
    mysqli_free_result($result);
}
