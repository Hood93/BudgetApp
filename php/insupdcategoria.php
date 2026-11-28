<?php
require_once __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metodo non consentito');
}
$id = trim((string)($_POST['editidcategoria'] ?? ''));
$editing = $id !== '';
$name = trim((string)($_POST[$editing ? 'editcategoria' : 'newcategoria'] ?? ''));
$icon = trim((string)($_POST['newiconacategoria'] ?? ''));
if ($name === '' || strlen($name) > 150 || strlen($icon) > 1000 || ($editing && (!ctype_digit($id) || (int)$id < 1))) {
    http_response_code(400);
    exit('Dati categoria non validi');
}
$months = [1=>'Gennaio',2=>'Febbraio',3=>'Marzo',4=>'Aprile',5=>'Maggio',6=>'Giugno',7=>'Luglio',8=>'Agosto',9=>'Settembre',10=>'Ottobre',11=>'Novembre',12=>'Dicembre'];
try {
    mysqli_begin_transaction($conn);
    if ($editing) {
        $lookup = mysqli_prepare($conn, 'SELECT categoria FROM categoriebudget WHERE idcategoria=? FOR UPDATE');
        mysqli_stmt_bind_param($lookup, 'i', $id);
        mysqli_stmt_execute($lookup);
        $res = mysqli_stmt_get_result($lookup);
        $previous = mysqli_fetch_assoc($res);
        mysqli_stmt_close($lookup);
        if (!$previous) {
            mysqli_rollback($conn);
            http_response_code(404);
            exit('Categoria non trovata');
        }
        $update = mysqli_prepare($conn, 'UPDATE categoriebudget SET categoria=? WHERE idcategoria=?');
        mysqli_stmt_bind_param($update, 'si', $name, $id);
        if (!mysqli_stmt_execute($update)) throw new RuntimeException('Category update failed');
        mysqli_stmt_close($update);
        $transactions = mysqli_prepare($conn, 'UPDATE budgettable SET categorie=? WHERE categorie=?');
        $oldName = $previous['categoria'];
        mysqli_stmt_bind_param($transactions, 'ss', $name, $oldName);
        if (!mysqli_stmt_execute($transactions)) throw new RuntimeException('Transaction update failed');
        mysqli_stmt_close($transactions);
    } else {
        $insert = mysqli_prepare($conn, 'INSERT INTO categoriebudget (categoria,icona) VALUES (?,?)');
        mysqli_stmt_bind_param($insert, 'ss', $name, $icon);
        if (!mysqli_stmt_execute($insert)) throw new RuntimeException('Category insert failed');
        mysqli_stmt_close($insert);
        $year = date('Y');
        $initial = mysqli_prepare($conn, 'INSERT INTO budgettable (data_operazione,mese,anno,ordinemese,causale,uscite,entrate,tag1,categorie) VALUES (?,?,?,?,?,?,?,?,?)');
        $cause = 'Iniziale';
        $zero = '0';
        $tag = 'Uscita';
        foreach ($months as $month => $monthName) {
            $date = sprintf('%s-%02d-01', $year, $month);
            $monthOrder = (string)$month;
            mysqli_stmt_bind_param($initial, 'sssssssss', $date, $monthName, $year, $monthOrder, $cause, $zero, $zero, $tag, $name);
            if (!mysqli_stmt_execute($initial)) throw new RuntimeException('Monthly initialization failed');
        }
        mysqli_stmt_close($initial);
    }
    mysqli_commit($conn);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Categoria salvata';
} catch (Throwable $exception) {
    mysqli_rollback($conn);
    error_log('BudgetApp category write failed: ' . $exception->getMessage());
    // MySQL 1062 is a duplicate key (e.g. a category name already present).
    $duplicate = $exception instanceof mysqli_sql_exception && (int)$exception->getCode() === 1062;
    http_response_code($duplicate ? 409 : 500);
    header('Content-Type: text/plain; charset=utf-8');
    echo $duplicate ? 'Esiste già una categoria con questo nome.' : 'Errore durante il salvataggio della categoria';
}
