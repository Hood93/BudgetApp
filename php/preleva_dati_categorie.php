<?php
//ini_set('display_errors', 1);
include "db.php";




$query = "SELECT * FROM categoriebudget order by categoria asc";


//Ciclo il risultato
$result = mysqli_query($conn, $query);
if (!$result) {
    echo "An error occurred.\n";
    exit;
}
echo "<div class='table-responsive'>";
echo "<table class='table table-hover align-middle'>
<thead>
<tr>";
echo "<th scope='col'><i class='fa-solid fa-icons fa-lg' style='color: #000000;'></i></th>";
echo "<th scope='col'>Categorie</th>";
echo "<th scope='col'>Azione</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td style='display:none;'>" . (int)$row["idcategoria"] . "</td>";
    // Preserve configured Font Awesome icons without injecting database HTML.
    $iconMarkup = (string)($row['icona'] ?? '');
    $iconClasses = 'fa-solid fa-folder';
    if (preg_match('/<i\\b[^>]*\\bclass\\s*=\\s*["\\']([^"\\']+)["\\']/i', $iconMarkup, $matches)) {
        $tokens = preg_split('/\\s+/', trim($matches[1]));
        $allowed = array_values(array_filter($tokens, static fn($token) => preg_match('/^(?:fa|fas|far|fab|fa-solid|fa-regular|fa-brands|fa-[a-z0-9-]+)$/', $token)));
        if ($allowed && count($allowed) === count($tokens)) $iconClasses = implode(' ', $allowed);
    }
    echo "<td class='text-start'><i class='" . htmlspecialchars($iconClasses, ENT_QUOTES, 'UTF-8') . "' aria-hidden='true'></i></td>";
    echo "<td class='text-start'>" . htmlspecialchars((string)$row['categoria'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . "<button type='button' data-bs-toggle='modal' data-bs-target='#ModalEditCategoria' class='btn btn-success btn-sm editbtn'><i class='fa-solid fa-pen-to-square fa-sm' style='color: #ffffff;'></i></button>" . "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</div>";
?>
<script>
    $(document).ready(function() {
        //Modal Edit

        $('.editbtn').on('click', function() {

            $('#editmodal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function() {
                return $(this).text();
            }).get();
            console.log(data);
            editidcategoria = data[0];
            editcategoria = data[2];

            $('#editidcategoria').val(editidcategoria);
            $('#editcategoria').val(editcategoria);
            //$('#editiconacategoria').val(editiconacategoria);

        });


    })
</script>