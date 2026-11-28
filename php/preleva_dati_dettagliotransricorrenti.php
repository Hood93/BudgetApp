<?php
require_once __DIR__ . '/db.php';
$id = filter_input(INPUT_POST, 'viewidtransricorrenti', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('<p class="ba-detail-empty">Transazione ricorrente non valida.</p>');
}
$sql = 'SELECT b.idBudgetTable, b.data_operazione, b.descrizione, b.entrate, b.uscite, b.tag1, b.categorie, c.icona
        FROM budgettable AS b
        LEFT JOIN categoriebudget AS c ON b.categorie = c.categoria
        WHERE b.idtransazioniricorrenti = ?
        ORDER BY b.data_operazione ASC';
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) { http_response_code(500); exit('<p class="ba-detail-empty">Impossibile caricare i dettagli.</p>'); }
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) { mysqli_stmt_close($stmt); http_response_code(500); exit('<p class="ba-detail-empty">Impossibile caricare i dettagli.</p>'); }
$escape = static fn($value): string => htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
$money = static fn($value): string => number_format((float)$value, 2, ',', '.') . ' €';
?>
<div class="table-responsive ba-recurring-detail-scroll">
    <table class="table ba-recurring-detail-table" id="table_DettaglioTransRicorrenti">
        <thead>
            <tr><th scope="col">Data Operazione</th><th scope="col">Descrizione</th><th scope="col">Entrate</th><th scope="col">Uscite</th><th scope="col">Tag1</th><th scope="col">Categoria</th></tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)):
            $income = (float)$row['entrate'];
            $expense = (float)$row['uscite'];
        ?>
            <tr>
                <td style="display:none;"><?= $escape($row['idBudgetTable']) ?></td>
                <td data-label="Data"><?= $escape($row['data_operazione']) ?></td>
                <td data-label="Descrizione"><?= $escape($row['descrizione']) ?></td>
                <td data-label="Entrate" class="<?= $income > 0 ? 'ba-amount-positive' : '' ?>"><?= $money($income) ?></td>
                <td data-label="Uscite" class="<?= $expense > 0 ? 'ba-amount-negative' : '' ?>"><?= $money($expense) ?></td>
                <td data-label="Tag"><?= $escape($row['tag1']) ?></td>
                <td data-label="Categoria"><?= $row['icona'] ?? '' ?> <?= $escape($row['categorie']) ?></td>
            </tr>
        <?php endwhile; mysqli_stmt_close($stmt); ?>
        </tbody>
    </table>
</div>
