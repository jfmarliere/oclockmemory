<?php

include_once "memoryBDD.class.php"; //on inclue les méthodes pour communiquer avec la base de donnée

class Carte {

    private $insertId; //id créer automatiquement en base de donnée qui sera récupérer lors d'une requête SQL
    private $numero; //numéro de la carte (0 à 17), chacun correspondant à un fruit
    private $perdue; //si elle n'est pas encore trouvée avec son double (true), ou si elle est trouvée avec son autre (false)

    //on utilise le constructeur pour lancer des actions au moment dde la création de notre objet
    /**
     * Carte constructor.
     * @param $numero
     * @param $perdue
     */
    public function __construct($numero, $visible = false, $perdue = true)
    {
        $this->numero = $numero; //numéro de la carte
        $this->perdue = $perdue; //elle sera en état 'perdue' par défault
    }

    public function sauvegarderCarteBDD() {
        $memoryBDD = new memoryBDD();
        $requeteSql = "INSERT INTO carte (numero, perdue) VALUES (".$this->numero.", ".$this->perdue.");";
        $memoryBDD->requeteSQL($requeteSql);
        return $memoryBDD->mysqli->insert_id;
    }

    /**
     * @return mixed
     */
    public function getInsertId()
    {
        return $this->insertId;
    }

    /**
     * @param mixed $insertId
     */
    public function setInsertId($insertId): void
    {
        $this->insertId = $insertId;
    }
     /**
     * @return int
     */
    public function getNumero(): int
    {
        return $this->numero;
    }

    /**
     * @param int $numero
     */
    public function setNumero(int $numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return bool
     */
    public function isPerdue(): bool
    {
        return $this->perdue;
    }

    /**
     * @param bool $perdue
     */
    public function setPerdue(bool $perdue): void
    {
        $this->perdue = $perdue;
    }

}
