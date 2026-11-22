<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

function recurringError(int $status, string $message): void {
    http_response_code($status);
    echo json_encode(['ok' => false, 'error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') recurringError(405, 'Metodo non consentito');

$dateInput = trim((string)($_POST['newdataoperazioneopric'] ?? ''));
$monthName = trim((string)($_POST['newmesepartopric'] ?? ''));
$description = trim((string)($_POST['newdescrizioneopric'] ?? ''));
$category = trim((string)($_POST['newcategoriaopric'] ?? ''));
$tag = trim((string)($_POST['newtag1opric'] ?? ''));
$durationInput = trim((string)($_POST['newdurataopric'] ?? ''));
$months = [1=>'Gennaio',2=>'Febbraio',3=>'Marzo',4=>'Aprile',5=>'Maggio',6=>'Giugno',7=>'Luglio',8=>'Agosto',9=>'Settembre',10=>'Ottobre',11=>'Novembre',12=>'Dicembre'];
$start = DateTimeImmutable::createFromFormat('!Y-m-d', $dateInput);
if (!$start || $start->format('Y-m-d') !== $dateInput ||
    !ctype_digit($durationInput) || (int)$durationInput < 1 || (int)$durationInput > 12 ||
    $description === '' || $category === '' || !in_array($tag, ['Entrata','Uscita'], true) ||
    !in_array($monthName, $months, true)) recurringError(400, 'Dati della ricorrenza non validi');

$startMonth = array_search($monthName, $months, true);
$start = $start->setDate((int)$start->format('Y'), $startMonth, 1);
$day = (int)substr($dateInput, 8, 2);
$duration = (int)$durationInput;
$endMonth = $start->modify('first day of +'.($duration-1).' months');
$incomeInput = trim((string)($_POST['newentrateopric'] ?? ''));
$expenseInput = trim((string)($_POST['newusciteopric'] ?? ''));
$parseAmount = static function(string $value): ?string {
    if ($value === '') return '0';
    if (!preg_match('/^\\d+(?:[.,]\\d{1,2})?$/', $value)) return null;
    return str_replace(',', '.', $value);
};
$income = $parseAmount($incomeInput);
$expense = $parseAmount($expenseInput);
if ($income === null || $expense === null) recurringError(400, 'Importo non valido');
if (($tag === 'Entrata' && (float)$expense > 0) || ($tag === 'Uscita' && (float)$income > 0)) recurringError(400, 'Importi non coerenti con la tipologia');

try {
    mysqli_begin_transaction($conn);
    $series = mysqli_prepare($conn, 'INSERT INTO transazioniricorrenti (meseiniziale,annoiniziale,mesefinale,annofinale,entrata,uscita,categoria,descrizione,stato) VALUES (?,?,?,?,?,?,?,?,?)');
    $movement = mysqli_prepare($conn, 'INSERT INTO budgettable (data_operazione,mese,anno,ordinemese,descrizione,uscite,entrate,categorie,tag1,idtransazioniricorrenti) VALUES (?,?,?,?,?,?,?,?,?,?)');
    if (!$series || !$movement) throw new RuntimeException('Prepare recurring inserts failed');
    $initialMonth = (int)$start->format('n');
    $initialYear = (int)$start->format('Y');
    $finalMonth = (int)$endMonth->format('n');
    $finalYear = (int)$endMonth->format('Y');
    $state = 'Attiva';
    mysqli_stmt_bind_param($series,'iiiisssss',$initialMonth,$initialYear,$finalMonth,$finalYear,$income,$expense,$category,$description,$state);
    if (!mysqli_stmt_execute($series)) throw new RuntimeException('Insert series failed: '.mysqli_stmt_error($series));
    $seriesId = mysqli_insert_id($conn);
    for ($i=0; $i<$duration; $i++) {
        $monthStart = $start->modify('first day of +'.$i.' months');
        $year = (int)$monthStart->format('Y');
        $month = (int)$monthStart->format('n');
        $actualDay = min($day, (int)$monthStart->format('t'));
        $operationDate = $monthStart->setDate($year,$month,$actualDay)->format('Y-m-d');
        $name = $months[$month];
        mysqli_stmt_bind_param($movement,'ssissssssi',$operationDate,$name,$year,$month,$description,$expense,$income,$category,$tag,$seriesId);
        if (!mysqli_stmt_execute($movement)) throw new RuntimeException('Insert recurring movement failed: '.mysqli_stmt_error($movement));
    }
    mysqli_commit($conn);
    echo json_encode(['ok'=>true,'inserted'=>$duration,'end'=>$endMonth->format('Y-m')],JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    mysqli_rollback($conn);
    error_log('BudgetApp recurring insert failed: '.$e->getMessage());
    recurringError(500, 'Impossibile salvare la ricorrenza');
}
