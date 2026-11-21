<?php
require_once __DIR__ . '/db.php';

$result = mysqli_query($conn, 'SELECT * FROM crediti ORDER BY idcrediti DESC');
if (!$result) {
    http_response_code(500);
    exit('Impossibile caricare i crediti');
}
$escape = static fn($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
echo '<section class="ba-credit-section"><div class="ba-report-heading"><div><h3>Crediti e finanziamenti</h3><p>Consulta i piani di rimborso e le rate pagate</p></div></div>';
echo '<div class="table-responsive ba-credit-scroll"><table class="table ba-credit-table"><thead><tr><th scope="col">Nome</th><th scope="col">Descrizione</th><th scope="col">Durata</th><th scope="col">Importo</th><th scope="col">Azione</th></tr></thead><tbody>';
$count = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $count++;
    $id = (int)$row['idcreditore'];
    $amount = number_format((float)$row['importo'], 2, ',', '.') . ' €';
    echo '<tr>';
    echo '<td class="ba-credit-name">' . $escape($row['creditore']) . '</td>';
    echo '<td class="ba-credit-description">' . $escape($row['descrizione']) . '</td>';
    echo '<td><span class="ba-credit-duration">' . $escape($row['durata']) . ' rate</span></td>';
    echo '<td class="ba-credit-amount">' . $amount . '</td>';
    echo '<td><button class="btn ba-credit-detail" type="button" idcreditore="' . $id . '" onclick="apriCollapse(this)" aria-label="Visualizza dettagli credito ' . $escape($row['creditore']) . '" aria-expanded="false"><i class="fa-solid fa-circle-info" aria-hidden="true"></i><span>Dettagli</span></button></td>';
    echo '</tr>';
}
if ($count === 0) echo '<tr><td colspan="5" class="ba-credit-empty">Nessun credito registrato.</td></tr>';
echo '</tbody></table></div><div class="collapse ba-credit-details" id="showdettagliocredito"></div></section>';
mysqli_free_result($result);
