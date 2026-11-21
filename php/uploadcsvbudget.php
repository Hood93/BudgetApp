<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/utility.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['formFile'])) {
    http_response_code(400);
    exit('File mancante');
}
$file = $_FILES['formFile'];
if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
    http_response_code(400);
    exit('Caricamento non riuscito');
}
if (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'csv' || $file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    exit('Caricare un CSV di massimo 5 MB');
}
$handle = fopen($file['tmp_name'], 'rb');
if (!$handle) {
    http_response_code(400);
    exit('Impossibile leggere il file');
}
$statement = null;
try {
    mysqli_begin_transaction($conn);
    $statement = mysqli_prepare($conn, 'INSERT INTO transazionipending (data_operazione,mese,anno,ordinemese,descrizione,uscite,entrate,tag1,descrizioneold,categorie) VALUES (?,?,?,?,?,?,?,?,?,?)');
    if (!$statement) throw new RuntimeException('Cannot prepare import');
    $line = 0;
    while (($row = fgetcsv($handle, 0, ';')) !== false) {
        $line++;
        if ($line === 1 || $row === [null]) continue;
        if (count($row) < 6) throw new InvalidArgumentException('CSV: colonne mancanti alla riga ' . $line);
        $rawDate = trim($row[0]);
        $date = DateTimeImmutable::createFromFormat('!d/m/Y', $rawDate)
            ?: DateTimeImmutable::createFromFormat('!d-m-Y', $rawDate);
        if (!$date || !in_array($date->format('d/m/Y'), [str_replace('-', '/', $rawDate)], true)) {
            throw new InvalidArgumentException('CSV: data non valida alla riga ' . $line);
        }
        $dateValue = $date->format('Y-m-d');
        $monthOrder = (int)$date->format('n');
        $year = $date->format('Y');
        $month = $mesi0[$date->format('m')] ?? $mesi0[$monthOrder] ?? null;
        if ($month === null) throw new RuntimeException('Month mapping missing');
        $original = trim($row[3]);
        $description = '';
        foreach ($descrizionearr as $needle => $replacement) {
            if ($needle !== '' && stripos($original, (string)$needle) !== false) {
                $description = (string)$replacement;
                break;
            }
        }
        $category = '';
        foreach ($catdescarr as $needle => $replacement) {
            if ($needle !== '' && stripos($description, (string)$needle) !== false) {
                $category = (string)$replacement;
                break;
            }
        }
        $normalizeAmount = static function ($value) {
            $value = trim(str_replace(['€', ' ', "\xc2\xa0"], '', (string)$value));
            if ($value === '') return '0';
            $value = str_replace('-', '', $value);
            if (str_contains($value, ',')) $value = str_replace(',', '.', str_replace('.', '', $value));
            if (!preg_match('/^[0-9]+(?:\\.[0-9]{1,2})?$/', $value)) throw new InvalidArgumentException('Importo CSV non valido');
            return $value;
        };
        $expense = $normalizeAmount($row[4]);
        $income = $normalizeAmount($row[5]);
        $tag = (float)$expense > 0 ? 'Uscita' : 'Entrata';
        $order = (string)$monthOrder;
        mysqli_stmt_bind_param($statement, 'ssssssssss', $dateValue, $month, $year, $order, $description, $expense, $income, $tag, $original, $category);
        if (!mysqli_stmt_execute($statement)) throw new RuntimeException('CSV insert failed');
    }
    mysqli_commit($conn);
    header('Location: ../Gestione.php', true, 303);
    exit;
} catch (InvalidArgumentException $exception) {
    mysqli_rollback($conn);
    http_response_code(400);
    echo htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
} catch (Throwable $exception) {
    mysqli_rollback($conn);
    error_log('BudgetApp CSV import failed: ' . $exception->getMessage());
    http_response_code(500);
    echo 'Errore durante l\'importazione CSV';
} finally {
    if (is_resource($handle)) fclose($handle);
    if ($statement instanceof mysqli_stmt) mysqli_stmt_close($statement);
}
