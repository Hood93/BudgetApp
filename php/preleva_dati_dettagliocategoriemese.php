<div class="table-responsive ba-detail-table-wrap">
    <table class="table table-hover align-middle ba-detail-table" id="table_DettaglioTransRicorrenti">
        <thead class="bg-light text-dark">
            <tr>
                <th scope="col">Data Operazione</th>
                <th scope="col">Descrizione</th>
                <th scope="col">Entrate</th>
                <th scope="col">Uscite</th>
                <th scope="col">Tag1</th>
                <th scope="col">Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php
             
            include "db.php";
            $datadioggi = date("d-m-Y");

            $categoria = (string)($_POST['categoria'] ?? '');
            $mese = (string)($_POST['mese'] ?? '');
            $anno = (string)($_POST['anno'] ?? '');
            $stmt = mysqli_prepare($conn, "SELECT b.*, c.icona FROM budgettable b LEFT JOIN categoriebudget c ON b.categorie = c.categoria WHERE b.mese = ? AND b.categorie = ? AND b.anno = ? ORDER BY b.data_operazione ASC");
            if (!$stmt) { http_response_code(500); exit('Impossibile caricare i dettagli.'); }
            mysqli_stmt_bind_param($stmt, 'sss', $mese, $categoria, $anno);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (!$result) { http_response_code(500); exit('Impossibile caricare i dettagli.'); }
            $escape = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
            $money = static fn($value) => number_format(abs((float)$value), 2, ',', '.') . ' €';

            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['causale'] == "Iniziale") {
                } elseif (strtotime($datadioggi) < strtotime($row['data_operazione'])) {
                }else{
                    $income = (float)$row['entrate'];
                    $expense = (float)$row['uscite'];
                    $icon = (string)($row['icona'] ?? '');
                    $iconClass = 'fa-solid fa-folder';
                    if (preg_match('~<i\\b[^>]*class\\s*=\\s*["\\\']([^"\\\']+)["\\\']~i', $icon, $matches)) {
                        $tokens = preg_split('/\\s+/', trim($matches[1]));
                        if ($tokens && count(array_filter($tokens, static fn($token) => preg_match('/^(?:fa|fas|far|fab|fa-solid|fa-regular|fa-brands|fa-[a-z0-9-]+)$/', $token))) === count($tokens)) {
                            $iconClass = implode(' ', $tokens);
                        }
                    }
                    echo "<tr>";
                    echo "<td id='idbudgettable' style='display:none;'>" . (int)$row["idBudgetTable"] . "</td>";
                    echo "<td>" . $escape($row['data_operazione']) . "</td>";
                    echo "<td>" . $escape($row['descrizione']) . "</td>";
                    echo "<td class='text-end " . ($income > 0 ? 'ba-amount-positive' : 'ba-amount-muted') . "'>" . $money($income) . "</td>";
                    echo "<td class='text-end " . ($expense != 0 ? 'ba-amount-negative' : 'ba-amount-muted') . "'>" . ($expense != 0 ? '−' : '') . $money($expense) . "</td>";
                    echo "<td>" . $escape($row['tag1']) . "</td>";
                    echo "<td><i class='" . $escape($iconClass) . "' aria-hidden='true'></i> " . $escape($row['categorie']) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

