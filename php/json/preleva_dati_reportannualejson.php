<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';

// The dashboard uses POST for the year filter. An empty selection means all years.
$year = trim((string)($_POST['cerca'] ?? date('Y')));
$sql = "SELECT ordinemese, mese, COALESCE(ROUND(SUM(CAST(uscite AS DECIMAL(15,2))),2),0) AS uscite, COALESCE(ROUND(SUM(CAST(entrate AS DECIMAL(15,2))),2),0) AS entrate FROM budgettable";
$params = [];
if ($year !== '') {
    if (!preg_match('/^[0-9]{4}$/', $year)) {
        http_response_code(400);
        echo json_encode(['error' => 'Anno non valido']);
        exit;
    }
    $sql .= " WHERE anno = ?";
    $params[] = $year;
}
$sql .= " GROUP BY ordinemese, mese ORDER BY ordinemese ASC";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Impossibile caricare il report']);
    exit;
}
if ($params) {
    mysqli_stmt_bind_param($stmt, "s", $params[0]);
}
if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    echo json_encode(['error' => 'Impossibile caricare il report']);
    exit;
}
$result = mysqli_stmt_get_result($stmt);
if ($result === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Impossibile leggere il report']);
    exit;
}
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $income = (float)$row['entrate'];
    $expense = (float)$row['uscite'];
    $rows[] = ['mese' => $row['mese'], 'entrate' => $income, 'uscite' => $expense, 'ris' => $income - $expense];
}
echo json_encode($rows, JSON_UNESCAPED_UNICODE);

mysqli_stmt_close($stmt);
