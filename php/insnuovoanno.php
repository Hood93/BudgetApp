<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';
$year = date('Y');
$months = [
    1 => 'Gennaio', 2 => 'Febbraio', 3 => 'Marzo', 4 => 'Aprile',
    5 => 'Maggio', 6 => 'Giugno', 7 => 'Luglio', 8 => 'Agosto',
    9 => 'Settembre', 10 => 'Ottobre', 11 => 'Novembre', 12 => 'Dicembre'
];
// Re-running the operation must not create duplicate opening rows.
$sql = "INSERT INTO budgettable (data_operazione,mese,anno,ordinemese,causale,uscite,entrate,tag1,categorie)
        SELECT ?, ?, ?, ?, 'Iniziale', 0, 0, '', c.categoria
        FROM categoriebudget c
        WHERE NOT EXISTS (
            SELECT 1 FROM budgettable b
            WHERE b.anno = ? AND b.ordinemese = ?
              AND b.causale = 'Iniziale' AND b.categorie = c.categoria
        )";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) { http_response_code(500); echo json_encode(['error'=>'Impossibile inizializzare l’anno']); exit; }
$inserted = 0;
try {
    mysqli_begin_transaction($conn);
    foreach ($months as $number => $monthName) {
        $date = sprintf('%s-%02d-01', $year, $number);
        mysqli_stmt_bind_param($stmt, 'sssisi', $date, $monthName, $year, $number, $year, $number);
        if (!mysqli_stmt_execute($stmt)) throw new RuntimeException('Insert failed');
        $inserted += mysqli_stmt_affected_rows($stmt);
    }
    mysqli_commit($conn);
    echo json_encode(['ok'=>true,'anno'=>$year,'inserite'=>$inserted], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    mysqli_rollback($conn);
    error_log('BudgetApp year initialization failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error'=>'Impossibile inizializzare l’anno']);
} finally {
    mysqli_stmt_close($stmt);
}
