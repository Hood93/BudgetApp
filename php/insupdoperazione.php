<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metodo non consentito');
}
$id = trim((string)($_POST['editidoperazione'] ?? ''));
$editing = $id !== '';
$prefix = $editing ? 'edit' : 'new';
$fields = ['dataoperazione', 'mese', 'anno', 'ordinemese', 'descrizione', 'uscite', 'entrate', 'tag1', 'categoria'];
$values = [];
foreach ($fields as $field) {
    $values[$field] = trim((string)($_POST[$prefix . $field] ?? ''));
}
if ($editing && (!ctype_digit($id) || (int)$id < 1)) {
    http_response_code(400);
    exit('Identificativo non valido');
}
$date = DateTimeImmutable::createFromFormat('!Y-m-d', $values['dataoperazione']);
if (!$date || $date->format('Y-m-d') !== $values['dataoperazione']) {
    http_response_code(400);
    exit('Data non valida');
}
if (($values['anno'] !== '' && $values['anno'] !== $date->format('Y')) || ($values['ordinemese'] !== '' && $values['ordinemese'] !== (string)(int)$date->format('n'))) {
    http_response_code(400);
    exit('Anno o mese non valido');
}
$monthNames = [1=>'Gennaio',2=>'Febbraio',3=>'Marzo',4=>'Aprile',5=>'Maggio',6=>'Giugno',7=>'Luglio',8=>'Agosto',9=>'Settembre',10=>'Ottobre',11=>'Novembre',12=>'Dicembre'];
$values['mese'] = $monthNames[(int)$date->format('n')];
$values['anno'] = $date->format('Y');
$values['ordinemese'] = (string)(int)$date->format('n');
if ($values['descrizione'] === '' || $values['categoria'] === '' || !in_array($values['tag1'], ['Entrata', 'Uscita'], true)) {
    http_response_code(400);
    exit('Descrizione, tipologia e categoria sono obbligatorie');
}
foreach (['entrate', 'uscite'] as $amount) {
    if ($values[$amount] === '') $values[$amount] = '0';
    if (!preg_match('/^-?[0-9]+(?:[.,][0-9]{1,2})?$/', $values[$amount])) {
        http_response_code(400);
        exit('Importo non valido');
    }
    $values[$amount] = str_replace(',', '.', $values[$amount]);
    if ((float)$values[$amount] < 0) {
        http_response_code(400);
        exit('Gli importi devono essere positivi');
    }
}
try {
    if ($editing) {
        $stmt = mysqli_prepare($conn, 'UPDATE budgettable SET data_operazione=?, mese=?, anno=?, ordinemese=?, descrizione=?, uscite=?, entrate=?, tag1=?, categorie=? WHERE idBudgetTable=?');
    } else {
        $stmt = mysqli_prepare($conn, 'INSERT INTO budgettable (data_operazione,mese,anno,ordinemese,descrizione,uscite,entrate,tag1,categorie) VALUES (?,?,?,?,?,?,?,?,?)');
    }
    if (!$stmt) throw new RuntimeException('Prepare failed');
    $args = [$values['dataoperazione'], $values['mese'], $values['anno'], $values['ordinemese'], $values['descrizione'], $values['uscite'], $values['entrate'], $values['tag1'], $values['categoria']];
    if ($editing) {
        $args[] = (int)$id;
        mysqli_stmt_bind_param($stmt, 'sssssssssi', ...$args);
    } else {
        mysqli_stmt_bind_param($stmt, 'sssssssss', ...$args);
    }
    if (!mysqli_stmt_execute($stmt)) throw new RuntimeException('Execute failed');
    mysqli_stmt_close($stmt);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Operazione salvata';
} catch (Throwable $exception) {
    error_log('BudgetApp operation write failed: ' . $exception->getMessage());
    http_response_code(500);
    echo 'Errore durante il salvataggio';
}
