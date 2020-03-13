<?php

class Grille
{
    private $grille; // contien le tableau html où se trouvent les cartes
    private $positionCartes = array();
    private $numero_partie;

    /**
     * Grille constructor.
     *  //Crée une grille qui contient nos Cartes postionnée aléatoirement
     */
    public function __construct()
    {
        //on créer un numéro de partie en utilisant le Timestamp actuel qui est unique afin de pouvoir retrouver la partie plus tard
        $this->numero_partie = time();
    }

    function dessinerGrille()
    {
        //on récupère la position de nos cartes
        $this->positionCartes = json_decode($this->chargerGrille());

        //on écrit notre tableau
        $this->grille = "<table><tr>";
        //nombre de colonne
        $colonne = 0;
        //génération des lignes du tableau, chaques cartes sera contenues dans une balise DIV dans une cellule de tableau (TD)
        //il y aurau 3 attributs : le name, et l'id de la carte, ainsi que la fonction appelée lorsque l'on clique (onclick), renvoyant l'objet cliqué actuel
        foreach ($this->positionCartes as $positionGrille => $carte) {
            $this->grille .= "
            <td>
                 <div class='nonVisible' name='carte-" . $carte->numero . "' id='case" . $positionGrille . "' onclick='verifierPaire(this)'></div>
            </td>";
            //au bout de 7 colonnes, on ferme la ligne de tableau et on en crée une nouvelle, pour avoir au final : 4 lignes de 7 colonnes (donc 28 cases, soit 14 paires)
            if ($colonne++ >= 6) {
                $this->grille .= "</tr><tr>";
                $colonne = 0;
            }
        }
        //on ferme le tableau
        $this->grille .= "</tr></table>";

        //on retourne notre tableau
        return $this->grille;
    }

    //on interroge la bdd pour récupérer le contenu d'une grille
    function chargerGrille()
    {
        //Création d'un tableau contenant la position de chacunes de cartes
        $memoryBDD = new memoryBDD();
        //requpete SQL qui sera exécutée
        $requeteSql = "select grille.position, carte.numero from grille, carte where grille.carte = carte.id and numero_partie = $this->numero_partie;";
        $memoryBDD->requeteSQL($requeteSql);

        //traitement du résultat de la requête
        //on mémorise le résultat de la requête dans un tableau
        $listePositionCartes = array();
        //tant que $resultat contien des lignes
        while ($ligne = $memoryBDD->getResultat()->fetch_array(MYSQLI_ASSOC)) {
            //on récupère le contenu de la ligne de résultat dans notre variable $listePositionCartes
            $listePositionCartes[] = $ligne;
        }
        //on ferme la connection
        $memoryBDD->closeSQL();

        //on remet dans l'orde les position en ordre croissant
        ksort($listePositionCartes);

        //on retourne le tableau au format json
        return json_encode($listePositionCartes);
    }

    //on va sauvegarder le contenu de la grille en base de donnée
    //chacunes des cartes ainsi que leur position dans la grille
    //on utilise la librairie mysqli (on aurait pu utiliser PDO)

    function genererNouvelleGrilleAvecCartes()
    {
        //Nous avons 18 cartes différentes, qui sont présentes en double dans la grille (soit présentes en paire)
        //on génére un tableau où on enregistre les deux même cartes (une paire donc) à la suite des autres
        for ($t = 0; $t < 14; $t++) {
            //on stocke la carte
            $this->positionCartes[] = new Carte($t, 0, 1);
            //on stocke une deuxième fois la même carte (afin de créer une paire)
            $this->positionCartes[] = new Carte($t, 0, 1);
        }
        //Ensuite on mélange le tableau grâce à shuffle() : la position aléatoire de nos cartes est générée.
        //cela évite d'avoir à gérer des problèmes de doublons si on utilise un générateur de nombre aléatoire
        shuffle($this->positionCartes);
        return $this->positionCartes;
    }

    //on enregistre l'association de la carte avec la grille et le numéro de partie générer par le constructeur
    function sauvegarderNouvelleGrille()
    {

        //on parcours le tableau pour récupérer les données de la grille : positions + cartes
        foreach ($this->positionCartes as $position => $carte) {
            //on sauvegarde la carte en BDD et en même temps on enregistre son ID créé en bdd dans la varible $id
            $id = $carte->sauvegarderCarteBDD();

            //on enregistre l'association de la carte avec la grille et le numéro de partie
            $this->sauvegarderPositionCarteDansLaGrille($position, $id);
        }
    }

    function sauvegarderPositionCarteDansLaGrille($position, $carte)
    {
        $memoryBDD = new memoryBDD();
        $requeteSql = "INSERT INTO grille (numero_partie, position, carte) VALUES ($this->numero_partie, $position, $carte);";
        $memoryBDD->requeteSQL($requeteSql);
        $memoryBDD->closeSQL();
    }
}
