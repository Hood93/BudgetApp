<?php
require_once __DIR__ . '/db.php';

$result = mysqli_query($conn, 'SELECT idcategoria, categoria, icona FROM categoriebudget ORDER BY categoria ASC');
if (!$result) {
    error_log('BudgetApp categories query failed: ' . mysqli_error($conn));
    http_response_code(500);
    exit('<p role="alert">Impossibile caricare le categorie.</p>');
}
$escape = static fn($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
?>
<div class="table-responsive">
<table class="table table-hover align-middle">
<thead><tr>
<th scope="col"><span class="visually-hidden">Icona</span><i class="fa-solid fa-icons" aria-hidden="true"></i></th>
<th scope="col">Categorie</th>
<th scope="col">Azione</th>
</tr></thead><tbody>
<?php while ($row = mysqli_fetch_assoc($result)):
    // Icon markup is stored in the database; retain only Font Awesome class names.
    $icon = (string)($row['icona'] ?? '');
    $iconClasses = 'fa-solid fa-folder';
    if (preg_match('~<i\\b[^>]*class\\s*=\\s*["\\\']([^"\\\']+)["\\\']~i', $icon, $matches)) {
        $tokens = preg_split('/\\s+/', trim($matches[1]));
        if ($tokens && count(array_filter($tokens, static fn($token) => preg_match('/^(?:fa|fas|far|fab|fa-solid|fa-regular|fa-brands|fa-[a-z0-9-]+)$/', $token))) === count($tokens)) {
            $iconClasses = implode(' ', $tokens);
        }
    }
?>
<tr>
<td><i class="<?php echo $escape($iconClasses); ?>" aria-hidden="true"></i></td>
<td><?php echo $escape($row['categoria']); ?></td>
<td>
<button type="button" data-bs-toggle="modal" data-bs-target="#ModalEditCategoria"
 class="btn btn-success btn-sm editbtn"
 data-category-id="<?php echo (int)$row['idcategoria']; ?>"
 data-category-name="<?php echo $escape($row['categoria']); ?>"
 aria-label="Modifica categoria <?php echo $escape($row['categoria']); ?>">
 <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
</button>
</td>
</tr>
<?php endwhile; mysqli_free_result($result); ?>
</tbody></table></div>
<script>
(function () {
    // Delegation avoids duplicate handlers when the category table is reloaded.
    $('#categorie').off('click.baCategoryEdit', '.editbtn')
        .on('click.baCategoryEdit', '.editbtn', function () {
            $('#editidcategoria').val($(this).attr('data-category-id'));
            $('#editcategoria').val($(this).attr('data-category-name'));
        });
})();
</script>
