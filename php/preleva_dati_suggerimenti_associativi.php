<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    $queries = [
        'descriptions' => 'SELECT nuovadescrizione AS description FROM descrizioneassociativa',
        'mappings' => 'SELECT descrizione AS description, categoria AS category FROM categoriaassociativa',
        'categories' => 'SELECT categoria AS category, icona AS icon FROM categoriebudget ORDER BY categoria',
    ];
    $data = [];
    foreach ($queries as $key => $query) {
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new RuntimeException('Lookup query failed: ' . $key);
        }
        $data[$key] = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$key][] = $row;
        }
        mysqli_free_result($result);
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    error_log('BudgetApp association suggestions: ' . $e->getMessage());
    http_response_code(500);
    echo '{"error":"Impossibile caricare i suggerimenti"}';
}
