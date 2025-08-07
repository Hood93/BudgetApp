<?php
include "db.php";

//Prelevo le categorie
$query = "SELECT categoria FROM categoriebudget order by categoria asc";

$result = mysqli_query($conn, $query);

if (!$result) {
  echo "An error occurred.\n";
  exit;
}

// Creo l'array di categorie
$catarray = [];
while ($row = mysqli_fetch_assoc($result)) {
  extract($row);
  array_push($catarray, $row["categoria"]);
}

//Creo Array Anni

$precanno = "";

$query = "SELECT anno FROM budgettable order by anno asc";
$tableanno = [];
$result = mysqli_query($conn, $query);
if (!$result) {
  echo "An error occurred.\n";
  exit;
}

while ($row = mysqli_fetch_assoc($result)) {
  extract($row);
  if ($row['anno'] == $precanno) {
  } else {
    array_push($tableanno, $row['anno']);
  }
}
$tableanno = array_unique($tableanno);

//Array Mesi

$mesi = ['1'=>'Gennaio','2'=>'Febbraio','3'=>'Marzo','4'=>'Aprile','5'=>'Maggio','6'=>'Giugno','7'=>'Luglio','8'=>'Agosto','9'=>'Settembre','10'=>'Ottobre','11'=>'Novembre','12'=>'Dicembre'];

//Array Mesi 0

$mesi0 = ['01'=>'Gennaio','02'=>'Febbraio','03'=>'Marzo','04'=>'Aprile','05'=>'Maggio','06'=>'Giugno','07'=>'Luglio','08'=>'Agosto','09'=>'Settembre','10'=>'Ottobre','11'=>'Novembre','12'=>'Dicembre'];


// Creazione di un'array associativo per descrizione

$descrizionearr = [];

$query = "SELECT * FROM descrizioneassociativa";
$result = mysqli_query($conn, $query);
if (!$result) {
  echo "An error occurred.\n";
  exit;
}

while ($row = mysqli_fetch_assoc($result)) {
  extract($row);
  $descrizionearr[$row["olddescrizione"]] = $row["nuovadescrizione"];
  }

  // Creazione di un'array associativo per categoria
$catdescarr = [];

  $query = "SELECT * FROM categoriaassociativa";
  $result = mysqli_query($conn, $query);
  if (!$result) {
    echo "An error occurred.\n";
    exit;
  }
  
  while ($row = mysqli_fetch_assoc($result)) {
    extract($row);
    $catdescarr[$row["descrizione"]] = $row["categoria"];
    }

//print_r($descrizionearr);
 

/*
//Array Associativo Negozi Descrizione
$descrizionearr = ["FONDI COMUNI DI INVESTIMENTO"=>"Fondi Investimento",
"Mutuo" => "Rata Mutuo",
"PRELIEVO CONTANTE" => "Prelievo Bancomat",
"Addebito Canone" => "Servizi Bancari",
"Assegni" => "Bonifico",
"Assegno" => "Bonifico",
"Bonifico" => "Bonifico",
"XXL MARKET" => "Spesa MD",
"MD SPA" => "Spesa MD",
"ESSELUNGA" => "Spesa Esselunga",
"CARREFOUR" => "Spesa Carrefour",
"Conad" => "Spesa Conad", 
"IGT LOTTERY" => "Stipendio",
"PENNY MARKET" => "Spesa",
"Paypal" => "PayPal",
"American Express" => "Saldo Carta di Credito",
"E.ON ENERGIA" => "Bolletta", 
"PRIMARK" => "Spesa Primark", 
"BRICOMAN" => "Spesa Bricoman", 
"TELEPASS" => "Addebito Telepass", 
"ZACCARIA FRUITS" => "Spesa Frutta", 
"MAURY" => "Spesa Maurys",
"TAMOIL" => "Rifornimento Auto",
"IPMATIC" => "Rifornimento Auto",
"BONCI CARBURANTI" => "Rifornimento Auto",
"IPEROIL SRL" => "Rifornimento Auto",
"AUROL NETTUNESE" => "Rifornimento Auto",
"SYNERGY COLOMBO" => "Rifornimento Auto",
"PETROL ROMA" => "Rifornimento Auto",
"PALESTRA MCFIT" => "Abbonamento Palestra",
"APP BREASY TORINO" => "Spesa Erogatore Automatico",
"VERS. SPORTELLO CONVENZIONATO" => "Versamento",
"BEST ENGAGE" => "Stipendio"];

echo "<br>";
print_r($descrizionearr);

//Array Associativo Descrizione Categoria
$catdescarr = ["Mutuo" => "Casa",
"Prelievo Bancomat" => "Prelievo",
"Servizi Bancari" => "Servizi",
"Rifornimento Auto" => "Automobile",
"Spesa" => "Spesa",
"PayPal" => "Paypal", 
"Stipendio" => "Stipendio", 
"Saldo Carta di Credito" => "Cdc", 
"Addebito Telepass" => "Telepass", 
"Bonifico" => "Bonifico",
"Abbonamento Palestra" => "Spesa",
"Versamento" => "Bonifico",
"Bolletta" => "Casa",
"Fondi Investimento" => "Fondi"];
*/