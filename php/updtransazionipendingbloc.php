<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metodo non consentito');
}
$items = $_POST['arraytranpendmod'] ?? null;
$operation = (string)($_POST['operazione'] ?? '');
if (!is_array($items) || !in_array($operation, ['inserimento', 'elimina'], true)) {
    http_response_code(400);
    exit('Richiesta non valida');
}
if (count($items) > 1000) {
    http_response_code(400);
    exit('Troppe transazioni');
}
try {
    mysqli_begin_transaction($conn);
    $insert = mysqli_prepare($conn, 'INSERT INTO budgettable (data_operazione,mese,anno,ordinemese,descrizione,uscite,entrate,tag1,categorie) VALUES (?,?,?,?,?,?,?,?,?)');
    $delete = mysqli_prepare($conn, 'DELETE FROM transazionipending WHERE idtransazionipending=?');
    $exists = mysqli_prepare($conn, 'SELECT idtransazionipending FROM transazionipending WHERE idtransazionipending=? FOR UPDATE');
    if (!$insert || !$delete || !$exists) throw new RuntimeException('Statement preparation failed');
    $processed = [];
    foreach ($items as $item) {
        if (!is_string($item)) throw new InvalidArgumentException('Invalid row');
        $parts = explode(';', $item);
        $id = trim($parts[0] ?? '');
        if (!ctype_digit($id) || (int)$id < 1) throw new InvalidArgumentException('Invalid ID');
        if (isset($processed[$id])) continue;
        $processed[$id] = true;
        mysqli_stmt_bind_param($exists, 'i', $id);
        mysqli_stmt_execute($exists);
        $result = mysqli_stmt_get_result($exists);
        $found = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        if (!$found) throw new RuntimeException('Pending transaction missing');
        if ($operation === 'inserimento') {
            if (count($parts) !== 10) throw new InvalidArgumentException('Invalid row format');
            [, $date, $month, $year, $monthOrder, $description, $income, $expense, $category, $tag] = $parts;
            $parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
            if (!$parsedDate || $parsedDate->format('Y-m-d') !== $date || !preg_match('/^[0-9]{4}$/', $year) || !ctype_digit($monthOrder) || (int)$monthOrder < 1 || (int)$monthOrder > 12) {
                throw new InvalidArgumentException('Invalid date');
            }
            foreach ([$income, $expense] as $amount) {
                if (!preg_match('/^-?[0-9]+(?:[.,][0-9]{1,2})?$/', $amount)) throw new InvalidArgumentException('Invalid amount');
            }
            $income = str_replace(',', '.', $income);
            $expense = str_replace(',', '.', $expense);
            mysqli_stmt_bind_param($insert, 'sssssssss', $date, $month, $year, $monthOrder, $description, $expense, $income, $tag, $category);
            if (!mysqli_stmt_execute($insert)) throw new RuntimeException('Insert failed');
        }
        mysqli_stmt_bind_param($delete, 'i', $id);
        if (!mysqli_stmt_execute($delete)) throw new RuntimeException('Delete failed');
    }
    mysqli_commit($conn);
    mysqli_stmt_close($insert);
    mysqli_stmt_close($delete);
    mysqli_stmt_close($exists);
    echo 'Data Inserted';
} catch (InvalidArgumentException $e) {
    mysqli_rollback($conn);
    http_response_code(400);
    echo 'Dati non validi';
} catch (Throwable $e) {
    mysqli_rollback($conn);
    error_log('BudgetApp pending batch failed: ' . $e->getMessage());
    http_response_code(500);
    echo 'Errore durante l\'operazione';
}
