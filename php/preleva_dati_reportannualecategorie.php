<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/utility.php';

$year = trim((string)($_POST['cerca'] ?? date('Y')));
if (!preg_match('/^[0-9]{4}$/', $year)) {
    http_response_code(400);
    exit('Anno non valido');
}
$categories = array_values(array_unique(array_map('strval', $catarray)));
$months = $mesi;
$matrix = [];
$totals = array_fill_keys($categories, 0.0);
foreach ($months as $number => $name) $matrix[$number] = array_fill_keys($categories, 0.0);

$stmt = mysqli_prepare($conn, 'SELECT ordinemese, categorie, COALESCE(SUM(entrate),0) AS entrate, COALESCE(SUM(uscite),0) AS uscite FROM budgettable WHERE anno = ? GROUP BY ordinemese, categorie');
if (!$stmt) {
    http_response_code(500);
    exit('Errore nel caricamento del riepilogo');
}
mysqli_stmt_bind_param($stmt, 's', $year);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    mysqli_stmt_close($stmt);
    http_response_code(500);
    exit('Errore nel caricamento del riepilogo');
}
while ($row = mysqli_fetch_assoc($result)) {
    $month = (int)$row['ordinemese'];
    $category = (string)$row['categorie'];
    if (!isset($matrix[$month]) || !array_key_exists($category, $totals)) continue;
    $value = (float)$row['entrate'] - (float)$row['uscite'];
    $matrix[$month][$category] += $value;
    $totals[$category] += $value;
}
mysqli_stmt_close($stmt);

$money = static fn(float $amount): string => number_format($amount, 2, ',', '.') . ' €';
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
echo '<section class="ba-category-report">';
echo '<div class="ba-report-heading"><div><h3>Riepilogo mensile per categoria</h3><p>Importi netti per categoria · ' . $escape($year) . '</p></div><span class="ba-report-hint"><i class="fa-solid fa-hand-pointer"></i> Seleziona un importo per i dettagli</span></div>';
echo '<div class="ba-category-scroll" role="region" aria-label="Riepilogo mensile delle categorie" tabindex="0">';
echo '<table id="rescate" class="table ba-category-table"><thead><tr><th scope="col">Mese</th>';
foreach ($categories as $category) echo '<th scope="col">' . $escape($category) . '</th>';
echo '<th scope="col">Totale</th></tr></thead><tbody>';
foreach ($months as $number => $name) {
    $rowTotal = array_sum($matrix[$number]);
    echo '<tr><th scope="row">' . $escape($name) . '</th>';
    foreach ($categories as $category) {
        $value = $matrix[$number][$category];
        $class = $value < 0 ? 'ba-money-negative' : ($value > 0 ? 'ba-money-positive' : 'ba-money-zero');
        echo '<td><button type="button" class="ba-category-cell ' . $class . '" onclick="Dettaglio(this)" data-mese="' . $escape($name) . '" data-anno="' . $escape($year) . '" data-categoria="' . $escape($category) . '" aria-label="Dettaglio ' . $escape($category . ' ' . $name) . '">' . $money($value) . '</button></td>';
    }
    echo '<td class="ba-category-total">' . $money($rowTotal) . '</td></tr>';
}
echo '</tbody><tfoot><tr><th scope="row">Totale</th>';
foreach ($categories as $category) echo '<td>' . $money($totals[$category]) . '</td>';
echo '<td>' . $money(array_sum($totals)) . '</td></tr></tfoot></table></div></section>';
?>
<script>
function Dettaglio(button) {
    const $button = $(button);
    const categoria = String($button.attr('data-categoria') || '');
    const mese = String($button.attr('data-mese') || '');
    const anno = String($button.attr('data-anno') || '');
    if (!categoria || !mese || !anno) return;
    $('#viewcategoriadettaglio').text(categoria);
    const amex = categoria === 'Cdc';
    $('#insertamex, #dettamex').css('visibility', amex ? 'visible' : 'hidden');
    $('#VistaDettaglioCategorieMese').modal('show');
    $.ajax({
        type: 'POST',
        url: 'php/preleva_dati_dettagliocategoriemese.php',
        data: { ajax: 1, categoria: categoria, mese: mese, anno: anno },
        success: function(response) { $('#dettagliocategoriemese').html(response); },
        error: function() { $('#dettagliocategoriemese').text('Impossibile caricare il dettaglio.'); }
    });
}
$(document).off('hidden.bs.modal.baCategory', '#VistaDettaglioCategorieMese')
    .on('hidden.bs.modal.baCategory', '#VistaDettaglioCategorieMese', function() {
        $('#dettaglioamex').collapse('hide');
    });
</script>