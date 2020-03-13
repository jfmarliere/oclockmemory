<?php
    include "Carte.class.php";
    include "Grille.class.php";
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="sass/memory.css">
    <script type="application/javascript" src="./js/jquery-3.4.1.min.js"></script>
    <script type="application/javascript" src="./js/memory.js"></script>
<body>
<div class="center">
    <?php
        $grille = new Grille(); //on instantie un nouvel objet Grille
        $grille->genererNouvelleGrilleAvecCartes(); //on génére une nouvelle grille avec des cartes aléatoires
        $grille->sauvegarderNouvelleGrille(); //on sauvegarde la grille et les cartes en base de donnée
        echo $grille->dessinerGrille() //on affiche la grille
    ?>
    <span id="duree" class="center duree">Durée :</span>
    <br>
    <div id="maProgression">
        <div id="barreDeProgression">0%</div>
    </div>
</div>
</body>
</html>
