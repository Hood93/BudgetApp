<?php
include "db.php";

    //$newdataopric = $_POST['newdataopric'];
    $newmesepartopric = $_POST['newmesepartopric']; //Mese Iniziale
    $newannoopric = $_POST['newannoopric']; //Anno di partenza
    $newordinemeseopric = $_POST['newordinemeseopric']; //Ordine del mese
    $newdescrizioneopric = $_POST['newdescrizioneopric']; //Descrizion Operazione Ricorrente
    $newusciteopric = $_POST['newusciteopric']; //Uscita operazione Ricorrente
    $newentrateopric = $_POST['newentrateopric']; //Entrata operazione ricorrente
    $newcategoriaopric = $_POST['newcategoriaopric']; //Categoria Operazione ricorrente
    $newdurataopric = $_POST['newdurataopric']; //Durata operazione Ricorrente
    $newnummesepartopric = $_POST['newnummesepartopric']; //Numero mese partenza operazione ricorrente
    $newnumgiornopartopric = $_POST['newnumgiornopartopric']; //Giorno del mese partenza operazione ricorrente
    $newtag1opric = $_POST['newtag1opric']; //Tag1 Operazione ricorrente
    $stato = 'Attiva'; 

    $newmesefinopric = $newnummesepartopric;
    $newannoiniziale = $newannoopric;
    $newannofinopric = $newannoiniziale;
    echo "new mese finale pre" . $newmesefinopric . "</br>"; 
    for ($i = 1; $i < $newdurataopric ; $i++){
      if ($newmesefinopric >= 12) {
        $newmesefinopric = 0;
        $newannofinopric = $newannoiniziale + 1;
        echo "new anno fin " . $newannofinopric  . "</br>";
        
        echo "new mese finale post + anno1" . $newmesefinopric  . "</br>";
      } else {       
        
        echo "new mese finale post" . $newmesefinopric  . "</br>";
      }
      echo $i . "</br>";
      $newmesefinopric = $newmesefinopric + 1;
      echo "new mese finale post" . $newmesefinopric  . "</br>";
    }

 

    //Inserisco la transazione nella tabella transazioni ricorrenti
    $querytabricorrenti = "INSERT INTO transazioniricorrenti(meseiniziale,annoiniziale,mesefinale,annofinale,entrata,uscita,categoria,descrizione,stato) 
    VALUES('$newordinemeseopric','$newannoopric','$newmesefinopric','$newannofinopric','$newentrateopric','$newusciteopric','$newcategoriaopric','$newdescrizioneopric','$stato')";
    
    mysqli_query($conn, $querytabricorrenti);
     
    
    
    //Prelevo l'id della transazioni per metterlo nella tabella budget

   $queryid = "SELECT idtransazioniricorrenti from transazioniricorrenti order by idtransazioniricorrenti  desc limit 1";

    $result = mysqli_query($conn, $queryid);

    while ($row = mysqli_fetch_assoc($result)){
      $idtransazioniricorrenti = $row['idtransazioniricorrenti'];
    }


    // Ciclo il numero di volte per cui bisogna inserire l'operazione
    for ($i = 1; $i <= $newdurataopric; $i++){
    $newdataopric = $newannoopric . "-" . $newnummesepartopric . "-" . $newnumgiornopartopric;


    $query = "INSERT INTO budgettable(data_operazione,mese,anno,ordinemese, descrizione, uscite, entrate,categorie,tag1,idtransazioniricorrenti) 
    VALUES('$newdataopric', '$newmesepartopric', '$newannoopric', '$newordinemeseopric', '$newdescrizioneopric', '$newusciteopric', '$newentrateopric','$newcategoriaopric','$newtag1opric','$idtransazioniricorrenti')";
    
    mysqli_query($conn, $query);

    //Incremento data, mese

    $newnummesepartopric = $newnummesepartopric + 1;

    if ($newnummesepartopric < 10){
    $newnummesepartopric = "0".$newnummesepartopric;} else {
        $newnummesepartopric = $newnummesepartopric;
    }
    $newordinemeseopric = $newordinemeseopric + 1;

    if ($newordinemeseopric > 12){
        $newannoopric = $newannoopric +1;
        $newordinemeseopric = 1;
        $newnummesepartopric ="0". 1;
    
    switch ($newordinemeseopric) {
        case "1":
         $newmesepartopric = "Gennaio";
          break;
        case "2":
            $newmesepartopric = "Febbraio";
          break;
        case "3":
            $newmesepartopric = "Marzo";
          break;
        case "4":
            $newmesepartopric = "Aprile";
          break;
        case "5":
            $newmesepartopric = "Maggio";
          break;
        case "6":
            $newmesepartopric = "Giugno";
          break;
        case "7":
            $newmesepartopric = "Luglio";
          break;
        case "8":
            $newmesepartopric = "Agosto";
          break;
        case "9":
            $newmesepartopric = "Settembre";
          break;
        case "10":
            $newmesepartopric = "Ottobre";
          break;
        case "11":
            $newmesepartopric = "Novembre";
          break;
        case "12":
            $newmesepartopric = "Dicembre";
          break;
        default:
          $newmesepartopric = "";
      }
    } else {
        switch ($newordinemeseopric) {
            case "1":
             $newmesepartopric = "Gennaio";
              break;
            case "2":
                $newmesepartopric = "Febbraio";
              break;
            case "3":
                $newmesepartopric = "Marzo";
              break;
            case "4":
                $newmesepartopric = "Aprile";
              break;
            case "5":
                $newmesepartopric = "Maggio";
              break;
            case "6":
                $newmesepartopric = "Giugno";
              break;
            case "7":
                $newmesepartopric = "Luglio";
              break;
            case "8":
                $newmesepartopric = "Agosto";
              break;
            case "9":
                $newmesepartopric = "Settembre";
              break;
            case "10":
                $newmesepartopric = "Ottobre";
              break;
            case "11":
                $newmesepartopric = "Novembre";
              break;
            case "12":
                $newmesepartopric = "Dicembre";
              break;
            default:
              $newmesepartopric = "";
          }
    }
}
