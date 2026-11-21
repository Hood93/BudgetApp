<?php
require_once __DIR__ . '/db.php';

// Disable recurring operations after their final month (inclusive).
// The former logic failed at the December/January boundary.
$currentPeriod = (int)date('Ym');
$stmt = mysqli_prepare($conn, "UPDATE transazioniricorrenti SET stato = 'Disattiva' WHERE stato = 'Attiva' AND (CAST(annofinale AS UNSIGNED) * 100 + CAST(mesefinale AS UNSIGNED)) < ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $currentPeriod);
    if (!mysqli_stmt_execute($stmt)) {
        error_log('BudgetApp recurring status update failed: ' . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);
} else {
    error_log('BudgetApp recurring status statement preparation failed');
}
