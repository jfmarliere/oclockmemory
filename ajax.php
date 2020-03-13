<?php
    include_once "memoryBDD.php";

    if(!empty($_GET['numero'])) {
        $numero = $_GET['numero'];
    } else {
        die("paramètre manquant...");
    }

/*
    if(!empty($_GET['partie'])) {
        $partie = $_GET['partie'];
    } else {
        die("paramètre manquant...");
    }
*/

    $memoryBDD = new memoryBDD();
    $requeteSql = "UPDATE carte SET perdue = '0' WHERE carte.numero = ".$numero;
    $memoryBDD->requeteSQL($requeteSql);
    $memoryBDD->closeSQL();
