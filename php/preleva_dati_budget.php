<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

function baFail(int $status, string $message): void {
    http_response_code($status);
    echo json_encode(['ok'=>false, 'error'=>$message], JSON_UNESCAPED_UNICODE);
    exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') baFail(405, 'Metodo non consentito');
$search = trim((string)($_POST['cerca'] ?? ''));
$year = trim((string)($_POST['anno'] ?? ''));
$month = trim((string)($_POST['mese'] ?? ''));
$tag = trim((string)($_POST['tag1'] ?? ''));
$category = trim((string)($_POST['categoria'] ?? ''));
$type = trim((string)($_POST['tipo'] ?? ''));
$cursor = trim((string)($_POST['cursor'] ?? ''));
$months = ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
if (($year !== '' && !preg_match('/^[0-9]{4}$/', $year)) ||
    ($month !== '' && !in_array($month, $months, true)) ||
    ($type !== '' && !in_array($type, ['Entrata','Uscita'], true)) ||
    strlen($search)>200 || strlen($tag)>100 || strlen($category)>200 || strlen($cursor)>200) {
    baFail(400, 'Filtri non validi');
}
// Use the application calendar date, not the MySQL server clock: a database host
// with an outdated date would otherwise hide every newer transaction.
$today = (new DateTimeImmutable('now', new DateTimeZone('Europe/Rome')))->format('Y-m-d');
$where = " WHERE b.causale <> 'Iniziale' AND b.data_operazione <= ?";
$params = [$today];
$types = 's';
if ($search !== '') {
    $where .= " AND (b.data_operazione LIKE ? OR b.causale LIKE ? OR b.descrizione LIKE ? OR b.entrate LIKE ? OR b.uscite LIKE ? OR b.tag1 LIKE ? OR b.categorie LIKE ? OR b.tag3 LIKE ? OR b.tag4 LIKE ?)";
    for ($i=0;$i<9;$i++) { $params[]='%'.$search.'%'; $types.='s'; }
}
foreach (['anno'=>$year,'mese'=>$month,'tag1'=>$tag,'categorie'=>$category] as $column=>$value) {
    if ($value !== '') { $where .= ' AND b.'.$column.' = ?'; $params[]=$value; $types.='s'; }
}
if ($type !== '') { $where .= ' AND b.tag1 = ?'; $params[]=$type; $types.='s'; }
function baQuery(mysqli $conn, string $sql, string $types, array $params): mysqli_result {
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) throw new RuntimeException('Prepare failed: '.mysqli_error($conn));
    if ($params && !mysqli_stmt_bind_param($stmt, $types, ...$params)) throw new RuntimeException('Bind failed');
    if (!mysqli_stmt_execute($stmt)) throw new RuntimeException('Execute failed: '.mysqli_stmt_error($stmt));
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) throw new RuntimeException('Result failed: '.mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    return $result;
}
try {
    $summary = mysqli_fetch_assoc(baQuery($conn,
        'SELECT COUNT(*) AS totale, COALESCE(SUM(b.entrate),0) AS entrate, COALESCE(SUM(b.uscite),0) AS uscite FROM budgettable b'.$where,
        $types, $params));
    $pageWhere=$where;
    $pageParams=$params;
    $pageTypes=$types;
    if ($cursor !== '') {
        $decoded=base64_decode($cursor,true);
        $parts=$decoded===false?[]:explode('|',$decoded);
        if (count($parts)!==2 || !preg_match('/^\\d{4}-\\d{2}-\\d{2}$/',$parts[0]) ||
            !ctype_digit($parts[1]) || (int)$parts[1]<1) baFail(400,'Cursore non valido');
        $pageWhere.=' AND (b.data_operazione < ? OR (b.data_operazione = ? AND b.idBudgetTable < ?))';
        array_push($pageParams,$parts[0],$parts[0],(int)$parts[1]);
        $pageTypes.='ssi';
    }
    $pageParams[]=31;
    $pageTypes.='i';
    $result=baQuery($conn,
        'SELECT b.idBudgetTable,b.data_operazione,b.descrizione,b.entrate,b.uscite,b.tag1,b.categorie,c.icona
         FROM budgettable b LEFT JOIN categoriebudget c ON b.categorie=c.categoria'.$pageWhere.
        ' ORDER BY b.data_operazione DESC,b.idBudgetTable DESC LIMIT ?',
        $pageTypes,$pageParams);
    $rows=[];
    while ($row=mysqli_fetch_assoc($result)) $rows[]=$row;
    mysqli_free_result($result);
    $hasMore=count($rows)>30;
    if ($hasMore) array_pop($rows);
    $last=$rows?end($rows):null;
    $next=$hasMore&&$last?base64_encode($last['data_operazione'].'|'.$last['idBudgetTable']):null;
    $escape=static fn($v):string=>htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8');
    $money=static fn($v):string=>number_format((float)$v,2,',','.').' €';
    $html='';
    foreach ($rows as $row) {
        $expense=$row['tag1']==='Uscita';
        $amount=$expense?$row['uscite']:$row['entrate'];
        $iconClass='fa-solid fa-receipt';
        $icon=(string)($row['icona']??'');
        if (preg_match('~<i\\b[^>]*class\\s*=\\s*["\\x27]([^"\\x27]+)["\\x27]~i',$icon,$match)) {
            $tokens=preg_split('/\\s+/',trim($match[1]));
            if ($tokens && count(array_filter($tokens,static fn($t)=>preg_match('/^(?:fa|fas|far|fab|fa-solid|fa-regular|fa-brands|fa-[a-z0-9-]+)$/',$t)))===count($tokens)) $iconClass=implode(' ',$tokens);
        }
        $edit=[$row['idBudgetTable'],$row['data_operazione'],$row['descrizione'],$row['entrate'],$row['uscite'],$row['tag1'],$row['categorie']];
        $editJson=json_encode($edit,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
        $period=substr($row['data_operazione'],0,7);
        $html.='<article class="ba-transaction-item '.($expense?'ba-expense':'ba-income').'" data-period="'.$escape($period).'">';
        $html.='<div class="ba-transaction-icon" aria-hidden="true"><i class="'.$escape($iconClass).'"></i></div>';
        $html.='<div class="ba-transaction-details"><div class="ba-transaction-category">'.$escape($row['categorie']).'</div>';
        $html.='<div class="ba-transaction-description">'.$escape($row['descrizione']).'</div>';
        $html.='<div class="ba-transaction-date"><i class="fa-regular fa-calendar me-1"></i>'.$escape($row['data_operazione']).'</div></div>';
        $html.='<div class="ba-transaction-actions"><div class="ba-transaction-amount">'.($expense?'− ':'+ ').$money($amount).'</div>';
        $html.='<button type="button" data-bs-toggle="modal" data-bs-target="#ModalEditOperazione" class="btn btn-outline-secondary editbtn" data-edit="'.$escape($editJson).'" aria-label="Modifica transazione"><i class="fa-solid fa-pen-to-square"></i></button></div></article>';
    }
    echo json_encode(['ok'=>true,'html'=>$html,'nextCursor'=>$next,'hasMore'=>$hasMore,
        'count'=>(int)$summary['totale'],'income'=>(float)$summary['entrate'],'expense'=>(float)$summary['uscite']],
        JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $e) {
    error_log('BudgetApp transaction pagination failed: '.$e->getMessage());
    baFail(500,'Impossibile caricare le transazioni');
}
