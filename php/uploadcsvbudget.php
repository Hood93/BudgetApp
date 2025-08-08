<?php
include "db.php";
include "utility.php";



if(isset($_POST['submit'])){
    $target_dir = "../upload/";
    $target_file = $target_dir . basename($_FILES["formFile"]["name"]);



    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

   

    $uploadOk = 1;
    if($imageFileType != "csv" ) {
         $uploadOk = 0;
         
    }

    if ($uploadOk != 0) {
         if (move_uploaded_file($_FILES["formFile"]["tmp_name"], $target_dir.'campioncinifantacalcio.csv')) {

            
              // Checking file exists or not
              $target_file = $target_dir . 'campioncinifantacalcio.csv';

              
              $fileexists = 0;
              if (file_exists($target_file)) {
                   $fileexists = 1;
              }
              if ($fileexists == 1 ) {

                   // Reading file
                   $file = fopen($target_file,"r");
                   $i = 0;

                   $importData_arr = array();
                    
                   while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
                        $num = count($data);
                        
                        for ($c=0; $c < $num; $c++) {
                             $importData_arr[$i][] = mysqli_real_escape_string($conn, $data[$c]);
                        }
                        $i++;
                   }
                   fclose($file);
                   
                   $skip = 0;


                   // insert import data
                   foreach($importData_arr as $data){
                

                        if($skip != 0){
                            $data_operazione = mysqli_real_escape_string($conn,$data[0]);
                            $data_operazione = str_replace("/","-",$data_operazione);
                            $divdataoperazione= explode("-",$data_operazione);
                            $data_operazione = $divdataoperazione[2] . "-" . $divdataoperazione[1] . "-" . $divdataoperazione[0]; 
                            $mese = $mesi0[$divdataoperazione[1]];
                            $anno = $divdataoperazione[2];
                            $ordinemese = $divdataoperazione[1];
                            if ($ordinemese < 10) {
                              $ordinemese =str_replace("0","",$ordinemese);
                            }


                            $descrizione = mysqli_real_escape_string($conn,$data[3]); 
                            
                            
                          // Test Descrizione Automatica
                          foreach ($descrizionearr as $chiave => $valore) {
                              //echo "descrizione è: " . $descrizione;
                              //echo "<br>";
                              //echo "<br>";
                              //echo "chiave da cercare: " . $chiave;
                              //echo "<br>";
                              //echo "<br>";
                              $resultstrposdesc = strpos(strtolower($descrizione),(strtolower($chiave)));
                              //echo "strpos " . $resultstrposdesc;
                              if ($resultstrposdesc === false){
                                   $descrizionenew = "";
                              } else {
                                   $descrizionenew = $descrizionearr[$chiave];
                                 //  echo "0descrizionenew " . $descrizionenew;
                                   //echo "<br>";
                                   break;
                              }
                          }
                          
                          
                          foreach ($catdescarr as $chiavecat => $valorecat) {
                             // echo "1nuova descrizione " . $descrizionenew;
                             // echo "<br>";
                              //echo "2chiave da cercare " . $chiavecat;

                            //  echo "<br>";
                             // echo "<br>";
                              $resultstrpos = strpos(strtolower($descrizionenew),(strtolower($chiavecat)));
                             // echo "strpos è:" . $resultstrpos . "<br>";
                             if ($resultstrpos === false){
                                   $categoria = "";
                              } else {
                                   $categoria = $catdescarr[$chiavecat];
                               //    echo "4nuova categoria" . $categoria;
                               //    echo "<br>";
                               //    echo "<br>";
                                   break;
                              }
                          }
                         
                         // End test descrizione automatica
                         $dasostituire = array("-","€");
                            $uscite = mysqli_real_escape_string($conn,str_replace($dasostituire,"",$data[4]));
                             if ($uscite == null) {
                              $uscite = 0;
                              $tag1 = "Entrata";
                             } 
                            $entrate = mysqli_real_escape_string($conn,str_replace($dasostituire,"",$data[5]));  
                            if ($entrate == null) {
                              $entrate = 0;
                              $tag1 = "Uscita";
                             }  
                            //$tag1 = mysqli_real_escape_string($conn,$data[6]);
                             //$tag1 = "";
                            
                                $query = "INSERT INTO transazionipending (data_operazione, mese, anno, ordinemese,descrizione,uscite,entrate,tag1,descrizioneold,categorie) VALUES ('" . $data_operazione . "', '" . $mese . "', '" . $anno . "', '" . $ordinemese . "', '" . $descrizionenew . "', '" . $uscite . "', '" . $entrate . "', '" . $tag1 . "', '" . $descrizione . "', '" . $categoria . "')";
                                $descrizionenew = "";
                                $categoria = ""; 
                                mysqli_query($conn, $query);
                              
                            
                        
                        }
                        $skip ++;
                   }
                   $newtargetfile = $target_file;
                   if (file_exists($newtargetfile)) {
                        unlink($newtargetfile);
                        header("location: ../Gestione.php");
                   }
              }
              
         }
    }
    
}
?>
