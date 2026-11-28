<?php
require_once __DIR__ . '/db.php';
$year = trim((string)($_POST['cerca'] ?? date('Y')));
if (!preg_match('/^[0-9]{4}$/', $year)) {
    http_response_code(400);
    exit('Anno non valido');
}
$stmt = mysqli_prepare($conn, 'SELECT ordinemese, mese, COALESCE(SUM(entrate),0) AS Entrate, COALESCE(SUM(uscite),0) AS Uscite FROM budgettable WHERE anno = ? GROUP BY ordinemese, mese ORDER BY ordinemese ASC');
if (!$stmt) { http_response_code(500); exit('Impossibile caricare il report'); }
mysqli_stmt_bind_param($stmt, 's', $year);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) { mysqli_stmt_close($stmt); http_response_code(500); exit('Impossibile caricare il report'); }
$money = static fn($value): string => number_format((float)$value, 2, ',', '.') . ' €';
$escape = static fn($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$totalIncome = 0.0;
$totalExpense = 0.0;
echo '<div class="table-responsive ba-annual-report-scroll"><table class="table ba-annual-report-table"><thead><tr><th scope="col">Mese</th><th scope="col">Entrate</th><th scope="col">Uscite</th><th scope="col">Differenza</th></tr></thead><tbody>';
while ($row = mysqli_fetch_assoc($result)) {
    $income = (float)$row['Entrate'];
    $expense = (float)$row['Uscite'];
    $balance = $income - $expense;
    $totalIncome += $income;
    $totalExpense += $expense;
    $balanceClass = $balance < 0 ? 'ba-amount-negative' : 'ba-amount-positive';
    echo '<tr><th scope="row" data-label="Mese">' . $escape($row['mese']) . '</th>';
    echo '<td data-label="Entrate">' . $money($income) . '</td>';
    echo '<td data-label="Uscite">' . $money($expense) . '</td>';
    echo '<td data-label="Differenza" class="' . $balanceClass . '">' . $money($balance) . '</td></tr>';
}
mysqli_stmt_close($stmt);
$balance = $totalIncome - $totalExpense;
$balanceClass = $balance < 0 ? 'ba-amount-negative' : 'ba-amount-positive';
echo '</tbody><tfoot><tr><th scope="row" data-label="Mese">Totale</th><td data-label="Entrate">' . $money($totalIncome) . '</td><td data-label="Uscite">' . $money($totalExpense) . '</td><td data-label="Differenza" class="' . $balanceClass . '">' . $money($balance) . '</td></tr></tfoot></table></div>';
