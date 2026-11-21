<?php
require_once __DIR__ . '/db.php';

// Existing callers send zero-based month indexes (January = 0).
$monthIndex = filter_var($_POST['mese'] ?? null, FILTER_VALIDATE_INT);
$year = trim((string)($_POST['anno'] ?? date('Y')));
if ($monthIndex === false || $monthIndex === null || $monthIndex < 0 || $monthIndex > 11 || !preg_match('/^[0-9]{4}$/', $year)) {
    http_response_code(400);
    exit('<p class="text-danger">Periodo non valido.</p>');
}
$month = $monthIndex + 1;
$names = ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
$stmt = mysqli_prepare($conn, 'SELECT COALESCE(ROUND(SUM(entrate),2),0) AS Entrate, COALESCE(ROUND(SUM(uscite),2),0) AS Uscite FROM budgettable WHERE ordinemese = ? AND anno = ?');
if (!$stmt) { http_response_code(500); exit('<p class="text-danger">Report non disponibile.</p>'); }
mysqli_stmt_bind_param($stmt, 'is', $month, $year);
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    http_response_code(500);
    exit('<p class="text-danger">Report non disponibile.</p>');
}
$result = mysqli_stmt_get_result($stmt);
$row = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);
if (!$row) { http_response_code(500); exit('<p class="text-danger">Report non disponibile.</p>'); }
$income = (float)$row['Entrate'];
$expense = (float)$row['Uscite'];
$balance = $income - $expense;
$money = static fn(float $value): string => number_format($value, 2, ',', '.') . ' €';
$balanceClass = $balance < 0 ? 'ba-amount-negative' : 'ba-amount-positive';
?>
<section aria-label="Riepilogo mensile">
  <h3 class="ba-month-report-title"><i class="fa-solid fa-chart-line" aria-hidden="true"></i><?php echo htmlspecialchars($names[$monthIndex] . ' ' . $year, ENT_QUOTES, 'UTF-8'); ?></h3>
  <div class="ba-month-report table-responsive">
    <table class="table align-middle">
      <thead><tr><th scope="col">Entrate</th><th scope="col">Uscite</th><th scope="col">Differenza</th></tr></thead>
      <tbody><tr>
        <td><?php echo $money($income); ?></td>
        <td><?php echo $money($expense); ?></td>
        <td class="<?php echo $balanceClass; ?>"><?php echo $money($balance); ?></td>
      </tr></tbody>
    </table>
  </div>
</section>
