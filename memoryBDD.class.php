<?php

/**
 * Class memoryBDD
 * Permet de gérer les connections et les requêtes avec MySQL
 */
class memoryBDD {
    /**
     * @var
     * objet contenant la connection mysql
     */
    private $mysql;
    /**
     * @var
     *objet contenant le résultat d'une requete mysql
     */
    private $resultat;

    /**
     * memoryBDD constructor.
     */
    function __construct()  {
        //on se connecte à la base de donnée (remplacer le port 3306 par 3307 si lancé via docker)
        $this->mysqli = new mysqli("127.0.0.1", "oclockmemory", "mdp#2020@secret", "oclockmemory", 3306);

        /* Vérification de la connexion */
        if ($this->mysqli->connect_errno) {
            die("Échec de la connexion : %s\n". $this->mysqli->connect_error);
        }
    }

    /**
     * @param $requeteSql
     * @return bool
     * fonction pour envoyer une requete SQL au serveur mysql
     */
    function requeteSQL($requeteSql) {

        if ($this->resultat = $this->mysqli->query($requeteSql)) {
            return true;
        }
        else {
            echo ("Erreur requête SQL : " . $this->mysqli->error . "\n" . $requeteSql);
            return false;
        }
    }

    /**
     * @return bool
     * Pour fermer la connection au serveur mysql
     */
    function closeSQL() {
        $this->mysqli->close();
        return true;
    }

    /**
     * @return mixed
     * pour récupérer le contenu de $resultat
     */
    public function getResultat()
    {
        return $this->resultat;
    }

}
