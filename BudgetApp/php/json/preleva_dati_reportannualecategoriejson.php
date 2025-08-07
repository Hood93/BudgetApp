<?php
//ini_set('display_errors', 1);
include "../db.php";
include "../utility.php";

$meseprec = "";



$querycat = "SELECT mese,categorie, ROUND(SUM(uscite),2) as Uscite,ROUND(SUM(entrate),2) as Entrate FROM budget.budgettable";
$querytotcat = "SELECT categorie,icona, ROUND(SUM(uscite),2) as Uscite,ROUND(SUM(entrate),2) as Entrate FROM budget.budgettable left join budget.categoriebudget on budget.budgettable.categorie = budget.categoriebudget.categoria";




if (isset($_POST["cerca"])) {
    $querycat .=
        " WHERE anno LIKE '%" . $_POST["cerca"] . "%'";
        $querytotcat .=
        " WHERE anno LIKE '%" . $_POST["cerca"] . "%'";
} else {
    $annoatt = date('Y');
    $querycat .=
    " WHERE anno LIKE '%" . $annoatt . "%'";
    $querytotcat .=
    " WHERE anno LIKE '%" . $annoatt . "%'";

}

$querycat .= " group by mese,categorie order by categorie asc";

$querytotcat .=  "  group by  categorie,icona order by categorie asc";


//Ciclo il risultato
$result = mysqli_query($conn, $querycat);
$resultcat = mysqli_query($conn,$querytotcat);

if (!$result) {
    echo "An error occurred.\n";
    exit;
}

while ($row = mysqli_fetch_assoc($result)) {


        if ($meseprec == $row['mese']){
          $entratecat = floatval($row['Entrate']);
           $uscitecat = floatval($row['Uscite']);
           $ris = $entratecat - $uscitecat;
           $arraygrafico[] = array('mese' => $row['mese'],'categoria'=> $row['categorie'],'ris' => $ris);
    } else {
        $entratecat = floatval($row['Entrate']);
        $uscitecat = floatval($row['Uscite']);
        $ris = $entratecat - $uscitecat;
        $arraygrafico[] = array('mese' => $row['mese'],'categoria'=> $row['categorie'],'ris' => $ris);
  
    }
  
$meseprec = $row['mese'];

}


echo json_encode($arraygrafico);
?>
