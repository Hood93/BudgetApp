<?php
include "db.php";
$search = trim((string)($_POST['cerca'] ?? ''));
$year = trim((string)($_POST['anno'] ?? ''));
$month = trim((string)($_POST['mese'] ?? ''));
$tag = trim((string)($_POST['tag1'] ?? ''));
$category = trim((string)($_POST['categoria'] ?? ''));
$sql = "SELECT b.*, c.icona FROM budget.budgettable b LEFT JOIN budget.categoriebudget c ON b.categorie = c.categoria WHERE b.causale <> 'Iniziale' AND b.data_operazione <= CURDATE()";
$values = [];
$types = '';
if ($search !== '') {
    $sql .= " AND (b.data_operazione LIKE ? OR b.causale LIKE ? OR b.descrizione LIKE ? OR b.entrate LIKE ? OR b.uscite LIKE ? OR b.tag1 LIKE ? OR b.categorie LIKE ? OR b.tag3 LIKE ? OR b.tag4 LIKE ?)";
    for ($i=0; $i<9; $i++) { $values[] = "%".$search."%"; $types .= 's'; }
}
foreach (['anno'=>$year,'mese'=>$month,'tag1'=>$tag,'categorie'=>$category] as $column=>$value) {
    if ($value !== '') { $sql .= " AND b.".$column." = ?"; $values[] = $value; $types .= 's'; }
}
$sql .= " ORDER BY b.data_operazione DESC";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) { http_response_code(500); echo '<p class="text-danger">Impossibile caricare le transazioni.</p>'; exit; }
if ($values) mysqli_stmt_bind_param($stmt, $types, ...$values);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$escape = static function ($value) { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8'); };
?>
<div class="ba-transaction-list">
<?php
$count = 0;
while ($row = mysqli_fetch_assoc($result)):
    $count++;
    $expense = $row['tag1'] === 'Uscita';
    $amount = $expense ? $row['uscite'] : $row['entrate'];
    $edit = implode(',', [$row['idBudgetTable'], $row['data_operazione'], $row['descrizione'], $row['entrate'], $row['uscite'], $row['tag1'], $row['categorie']]);
?>
  <article class="ba-transaction-item <?php echo $expense ? 'ba-expense' : 'ba-income'; ?>">
    <div class="ba-transaction-icon" aria-hidden="true"><?php echo $row['icona'] ?: '<i class="fa-solid fa-receipt"></i>'; ?></div>
    <div class="ba-transaction-details">
      <div class="ba-transaction-category"><?php echo $escape($row['categorie']); ?></div>
      <div class="ba-transaction-description"><?php echo $escape($row['descrizione']); ?></div>
      <div class="ba-transaction-date"><i class="fa-regular fa-calendar me-1"></i><?php echo $escape($row['data_operazione']); ?></div>
    </div>
    <div class="ba-transaction-actions">
      <div class="ba-transaction-amount"><?php echo $expense ? '−' : '+'; ?> <?php echo $escape($amount); ?> €</div>
      <button type="button" data-bs-toggle="modal" data-bs-target="#ModalEditOperazione" class="btn btn-outline-secondary editbtn" onclick="EditOperazione(this)" data="<?php echo $escape($edit); ?>" aria-label="Modifica transazione"><i class="fa-solid fa-pen-to-square"></i></button>
    </div>
  </article>
<?php endwhile; ?>
<?php if ($count === 0): ?><div class="ba-empty-state"><i class="fa-regular fa-folder-open"></i><p>Nessuna transazione trovata.</p></div><?php endif; ?>
</div>

<script>
    $(document).ready(function() {

        /*$('.editbtn').on('click', function() {

            $('#editmodal').modal('show');

            //$tr = $(this).closest('tr');
            //var data = $tr.children("td").map(function() {
            //    return $(this).text();
            //}).get();
            var data = $('#btnedit').attr('data')
            console.log(data)
            data = data.split(',')

 
            editidoperazione = data[0];
            editdataoperazione = data[1].replaceAll('/', '-');
            //editcausale = data[2];
            editdescrizione = data[2];
            editentrate = data[3];
            edituscite = data[4];
            //editentrate = editentrate.replace("€", "");
            //edituscite = edituscite.replace("€", "");
            //editentrate = editentrate.replace(/\s+/g, '');
            //edituscite = edituscite.replace(/\s+/g, '');
            edittag1 = data[5];
            editcategoria = data[6];
            //edittag3 = data[8];
            //edittag4 = data[9];

            $('#editidoperazione').val(editidoperazione);
            $('#editdataoperazione').val(editdataoperazione);
            //$('#editcausale').val(editcausale);
            $('#editdescrizione').val(editdescrizione);
            $('#editentrate').val(editentrate);
            $('#edituscite').val(edituscite);
            $('#edittag1').val(edittag1);
            $('#editcategoria').val(editcategoria);
            //$('#edittag3').val(edittag3);
            //$('#edittag4').val(edittag4);
        });*/
    });


    function EditOperazione(_this){
        $('#editmodal').modal('show');

            //$tr = $(this).closest('tr');
            //var data = $tr.children("td").map(function() {
            //    return $(this).text();
            //}).get();
            var data = $(_this).attr('data')
            console.log(data)
            data = data.split(',')

 
            editidoperazione = data[0];
            editdataoperazione = data[1].replaceAll('/', '-');
            //editcausale = data[2];
            editdescrizione = data[2];
            editentrate = data[3];
            edituscite = data[4];
            //editentrate = editentrate.replace("€", "");
            //edituscite = edituscite.replace("€", "");
            //editentrate = editentrate.replace(/\s+/g, '');
            //edituscite = edituscite.replace(/\s+/g, '');
            edittag1 = data[5];
            editcategoria = data[6];
            //edittag3 = data[8];
            //edittag4 = data[9];

            $('#editidoperazione').val(editidoperazione);
            $('#editdataoperazione').val(editdataoperazione);
            //$('#editcausale').val(editcausale);
            $('#editdescrizione').val(editdescrizione);
            $('#editentrate').val(editentrate);
            $('#edituscite').val(edituscite);
            $('#edittag1').val(edittag1);
            $('#editcategoria').val(editcategoria);
            //$('#edittag3').val(edittag3);
            //$('#edittag4').val(edittag4);
    }
</script>