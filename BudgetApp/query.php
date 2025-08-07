<?php
include "php/db.php";


$query = "CREATE TABLE `pianocrediti` (
    `idpianocrediti` int(11) NOT NULL AUTO_INCREMENT,
    `creditore` varchar(100) DEFAULT NULL,
    `rata` varchar(100) DEFAULT NULL,
    `importo` varchar(100) DEFAULT NULL,
    `checkpagato` varchar(100) DEFAULT NULL,
    `descrizione` varchar(100) DEFAULT NULL,
    `idcreditore` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`idpianocrediti`)
  ) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;";

mysqli_query($conn, $query);
?>