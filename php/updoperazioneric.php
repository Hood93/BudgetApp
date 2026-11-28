<?php
require_once __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metodo non consentito');
}
$id = trim((string)($_POST['delidtransricorrenti'] ?? ''));
if (!ctype_digit($id) || (int)$id < 1) {
    http_response_code(400);
    exit('Identificativo non valido');
}
try {
    mysqli_begin_transaction($conn);
    $deleteFuture = mysqli_prepare($conn, 'DELETE FROM budgettable WHERE idtransazioniricorrenti = ? AND data_operazione >= CURDATE()');
    $deleteSeries = mysqli_prepare($conn, 'DELETE FROM transazioniricorrenti WHERE idtransazioniricorrenti = ?');
    if (!$deleteFuture || !$deleteSeries) throw new RuntimeException('Cannot prepare recurring deletion');
    mysqli_stmt_bind_param($deleteFuture, 'i', $id);
    mysqli_stmt_bind_param($deleteSeries, 'i', $id);
    if (!mysqli_stmt_execute($deleteFuture) || !mysqli_stmt_execute($deleteSeries)) throw new RuntimeException('Recurring deletion failed');
    if (mysqli_stmt_affected_rows($deleteSeries) === 0) {
        mysqli_rollback($conn);
        http_response_code(404);
        exit('Ricorrenza non trovata');
    }
    mysqli_commit($conn);
    mysqli_stmt_close($deleteFuture);
    mysqli_stmt_close($deleteSeries);
    echo 'Data Inserted';
} catch (Throwable $exception) {
    mysqli_rollback($conn);
    error_log('BudgetApp recurring deletion failed: ' . $exception->getMessage());
    http_response_code(500);
    echo 'Errore durante la cancellazione';
}
