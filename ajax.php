<?php
    /*
    * réceptionne un numéro de carte envoyé en GET, et change sont état carte.perdue = 1 à carte.perdue = 0
    */

    //charge les fontions de bdd
    include_once "memoryBDD.php";

    //on vérifie que le numéro de carte est bien reçu
    if(!empty($_GET['numero'])) {
        $numero = $_GET['numero'];
    } else {
        die("paramètre manquant...");
    }

    $memoryBDD = new memoryBDD();
    //requete pour mettre à jour la carte
    $requeteSql = "UPDATE carte SET perdue = '0' WHERE carte.numero = ".$numero;
    $memoryBDD->requeteSQL($requeteSql);
    $memoryBDD->closeSQL();
