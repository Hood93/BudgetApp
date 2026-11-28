<?php
require_once __DIR__ . '/db.php';
$id = trim((string)($_POST['idcreditore'] ?? ''));
if (!ctype_digit($id) || (int)$id < 1) {
    http_response_code(400);
    exit('Credito non valido');
}
$stmt = mysqli_prepare($conn, 'SELECT * FROM pianocrediti WHERE idcreditore = ? ORDER BY rata ASC');
if (!$stmt) {
    http_response_code(500);
    exit('Impossibile caricare le rate');
}
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    mysqli_stmt_close($stmt);
    http_response_code(500);
    exit('Impossibile caricare le rate');
}
$escape = static fn($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
echo '<div class="ba-credit-detail-heading"><h4>Dettaglio delle rate</h4></div><div class="table-responsive"><table class="table table-hover align-middle" id="table_DettaglioCredito"><thead><tr><th scope="col">Nome</th><th scope="col">Descrizione</th><th scope="col">Rata</th><th scope="col">Importo</th><th scope="col">Pagata</th></tr></thead><tbody>';
$count = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $count++;
    $checked = (string)$row['checkpagato'] === 'checked' ? ' checked' : '';
    echo '<tr><td>' . $escape($row['creditore']) . '</td><td>' . $escape($row['descrizione']) . '</td><td>' . $escape($row['rata']) . '</td><td class="ba-credit-amount">' . number_format((float)$row['importo'], 2, ',', '.') . ' €</td>';
    echo '<td><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" aria-label="Rata ' . $escape($row['rata']) . ' pagata" idcreditore="' . (int)$row['idcreditore'] . '" rata="' . $escape($row['rata']) . '" onchange="updateCheckPagato(this)"' . $checked . '></div></td></tr>';
}
if ($count === 0) echo '<tr><td colspan="5" class="ba-credit-empty">Nessuna rata registrata.</td></tr>';
echo '</tbody></table></div>';
mysqli_stmt_close($stmt);
?>
<script>
function updateCheckPagato(input) {
    const $input = $(input);
    const checked = $input.is(':checked');
    $input.prop('disabled', true);
    $.ajax({
        type: 'POST',
        url: './php/update_dettagliocreditore.php',
        data: {
            ajax: 1,
            idcreditore: $input.attr('idcreditore'),
            rata: $input.attr('rata'),
            esito: checked ? 'checked' : ''
        },
        success: function() { $input.prop('disabled', false); },
        error: function() {
            $input.prop('checked', !checked).prop('disabled', false);
            alert('Impossibile aggiornare lo stato della rata.');
        }
    });
}
</script>