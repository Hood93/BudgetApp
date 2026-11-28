<?php
require_once __DIR__ . '/db.php';

$category = trim((string)($_POST['categoria'] ?? ''));
$month = trim((string)($_POST['mese'] ?? ''));
$year = trim((string)($_POST['anno'] ?? ''));
$months = ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
if ($category === '' || !in_array($month, $months, true) || !preg_match('/^[0-9]{4}$/', $year)) {
    http_response_code(400);
    exit('<p role="alert">Filtri non validi.</p>');
}
$sql = "SELECT b.idBudgetTable, b.data_operazione, b.descrizione, b.entrate, b.uscite,
               b.tag1, b.categorie, c.icona
        FROM budgettable b
        LEFT JOIN categoriebudget c ON b.categorie = c.categoria
        WHERE b.mese = ? AND b.categorie = ? AND b.anno = ?
          AND (b.causale IS NULL OR b.causale <> 'Iniziale') AND b.data_operazione <= CURDATE()
        ORDER BY b.data_operazione ASC, b.idBudgetTable ASC";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    error_log('BudgetApp category detail prepare failed: ' . mysqli_error($conn));
    http_response_code(500);
    exit('<p role="alert">Impossibile caricare i dettagli.</p>');
}
mysqli_stmt_bind_param($stmt, 'sss', $month, $category, $year);
if (!mysqli_stmt_execute($stmt) || !($result = mysqli_stmt_get_result($stmt))) {
    error_log('BudgetApp category detail query failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    http_response_code(500);
    exit('<p role="alert">Impossibile caricare i dettagli.</p>');
}
$escape = static fn($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$money = static fn($value): string => number_format(abs((float)$value), 2, ',', '.') . ' €';
?>
<div class="table-responsive ba-detail-table-wrap" role="region" aria-label="Dettaglio transazioni per categoria" tabindex="0">
<table class="table table-hover align-middle ba-detail-table" id="table_DettaglioTransRicorrenti">
<thead><tr>
<th scope="col">Data Operazione</th><th scope="col">Descrizione</th>
<th scope="col">Entrate</th><th scope="col">Uscite</th>
<th scope="col">Tag1</th><th scope="col">Categoria</th>
</tr></thead><tbody>
<?php
$count = 0;
while ($row = mysqli_fetch_assoc($result)):
    $count++;
    $income = (float)$row['entrate'];
    $expense = (float)$row['uscite'];
    $iconClass = 'fa-solid fa-folder';
    $icon = (string)($row['icona'] ?? '');
    if (preg_match('~<i\\b[^>]*class\\s*=\\s*["\\\']([^"\\\']+)["\\\']~i', $icon, $matches)) {
        $tokens = preg_split('/\\s+/', trim($matches[1]));
        if ($tokens && count(array_filter($tokens, static fn($token) => preg_match('/^(?:fa|fas|far|fab|fa-solid|fa-regular|fa-brands|fa-[a-z0-9-]+)$/', $token))) === count($tokens)) {
            $iconClass = implode(' ', $tokens);
        }
    }
?>
<tr>
<td><?php echo $escape($row['data_operazione']); ?></td>
<td><?php echo $escape($row['descrizione']); ?></td>
<td class="text-end <?php echo $income > 0 ? 'ba-amount-positive' : 'ba-amount-muted'; ?>"><?php echo $money($income); ?></td>
<td class="text-end <?php echo $expense != 0 ? 'ba-amount-negative' : 'ba-amount-muted'; ?>"><?php echo $expense != 0 ? '−' : ''; ?><?php echo $money($expense); ?></td>
<td><?php echo $escape($row['tag1']); ?></td>
<td><i class="<?php echo $escape($iconClass); ?>" aria-hidden="true"></i> <?php echo $escape($row['categorie']); ?></td>
</tr>
<?php endwhile;
if ($count === 0): ?>
<tr><td colspan="6" class="text-center">Nessuna transazione disponibile per il periodo selezionato.</td></tr>
<?php endif;
mysqli_free_result($result);
mysqli_stmt_close($stmt);
?>
</tbody></table></div>
