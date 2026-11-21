<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../db.php';
$year = trim((string)($_POST['cerca'] ?? date('Y')));
if ($year !== '' && !preg_match('/^[0-9]{4}$/', $year)) {
    http_response_code(400);
    echo json_encode(['error' => 'Anno non valido']);
    exit;
}
$sql = "SELECT categorie, ROUND(SUM(ABS(CAST(uscite AS DECIMAL(15,2)))),2) AS totale
        FROM budgettable WHERE causale <> 'Iniziale' AND uscite <> 0";
if ($year !== '') $sql .= ' AND anno = ?';
$sql .= ' GROUP BY categorie HAVING totale > 0 ORDER BY totale DESC LIMIT 5';
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) { http_response_code(500); echo json_encode(['error'=>'Report non disponibile']); exit; }
if ($year !== '') mysqli_stmt_bind_param($stmt, 's', $year);
if (!mysqli_stmt_execute($stmt)) { http_response_code(500); echo json_encode(['error'=>'Report non disponibile']); exit; }
$result = mysqli_stmt_get_result($stmt);
if (!$result) { http_response_code(500); echo json_encode(['error'=>'Report non disponibile']); exit; }
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = ['categoria'=>(string)$row['categorie'], 'totale'=>(float)$row['totale']];
}
mysqli_stmt_close($stmt);
echo json_encode($rows, JSON_UNESCAPED_UNICODE);
