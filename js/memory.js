let premiereCarte = false; //premiere carte cliquée
let deuxiemeCarte = false; //deuxieme carte cliquée
let barre = 0; //% de la barre de progression
let paires = 0; //nombre de paires trouvées
let s = 0; //variable contenant le nombre de secondes
let ajoutSecondes = 1; //nombre de secondes ajouté à S à chaque interval

//fonction pour comparer les 2 carte cliquées, prenant en paramètre la dernière carte que l'on a cliquée
function verifierPaire(carteCliquee) {
    if (premiereCarte === false) { //si pas de première carte
        premiereCarte = carteCliquee; //on mémorise la première carte
        afficherCarte(premiereCarte); //on l'affiche
    } else if (deuxiemeCarte === false) { //alors si la première carte est mémorisée et que la deuxième carte vide
        deuxiemeCarte = carteCliquee; //on mémorise la deuxième carte
        afficherCarte(deuxiemeCarte); //on affiche la deuxième carte
        if (premiereCarte.getAttribute('id') !== deuxiemeCarte.getAttribute('id')) {  //pour empêcher de cliquer deux fois sur la même case
            if (premiereCarte.getAttribute('name') === deuxiemeCarte.getAttribute('name')) { //ensuite on compare les deux cartes et si c'est le mêmes :
                majBarreDeProgression(7.14); //on met à jour la barre de progression
                sauvegarderPaireEnBDD(carteCliquee); // on sauvegarde la découverte de la paire en base de donnée
                afficherCarte(premiereCarte);
                afficherCarte(deuxiemeCarte);
                premiereCarte = false; //on remet à false notre carte pour pouvoir recommencer à selectionner une première carte
                deuxiemeCarte = false; //on remet à false notre carte pour pouvoir recommencer à selectionner une deuxieme carte
            } else { //alors si les deux cartes sont différentes, ce n'est pas une paire
                setTimeout(() => { //on place le code suivant dans une fonction anonyme pour que tout se déroule dans le même cycle de 1 seconde
                    // cela permet de voir la deuxième carte avant qu'elle ne soit cachée à nouveau. Sans le setTimeout() la deuxième carte resterai cachée
                    cacherCarte(premiereCarte);
                    cacherCarte(deuxiemeCarte);
                    premiereCarte = false; //on remet à false notre première carte pour pouvoir recommencer à en selectionner une
                    deuxiemeCarte = false; //on remet à false notre deuxieme carte pour pouvoir recommencer à en selectionner une
                }, 1000); // -> le parametre de durée du setTimeout(), soit 1000ms = 1 seconde
            }
        }
    }
}

//fonction pour le chronometre en secondes
$(function () {
    function duree() {
        s = s + ajoutSecondes; //on incrémente les secondes
        $('#duree').html('Durée : '+s+' secondes.');  //on met à jour le texte de la duree
    }
    setInterval(duree, 1000); //on execute la fonction duree toutes les secondes
});

//met à jour la barre de pourcentage de la barre de progression
function majBarreDeProgression(val) {
    val = Math.floor(val);   //on arrandi à l'entier inférieur
    barre = barre + val;  //on augmente la barre de progression
    paires = paires + 1;  //on ajoute une paire au au total de paires trouvées
    if (paires >= 14) {   //si les 14 paires sont trouvées, parties gagnée !
        barre = 100; //la barre passe à 100%
        ajoutSecondes = 0; // on stop l'incrémentation du compteur s pour bloquer la durée
        alert('Partie Gagnée !!!\n En ' + s + ' secondes'); //on affiche un message (popup)
    }
    $('#barreDeProgression').width(barre + '%'); //on met à jour la largeur de la barre
    $('#barreDeProgression').html(barre + '%'); //on met à jour le texte de la barre
}

function afficherCarte(carte) {
    $(carte).removeClass('nonVisible'); //on retire la calsse qui cache la carte
    $(carte).addClass(carte.getAttribute('name')); //on ajoute la classe qui montre la carte
}

function cacherCarte(carte) {
    $(carte).removeClass(carte.getAttribute('name')); //on retire la classe qui montre la carte
    $(carte).addClass('nonVisible'); //on ajoute la calsse qui cache la carte
}
//on change l'attribut perdu à 0 dans la BDD en utilisant ajax et le fichier ajax.php
function sauvegarderPaireEnBDD(carte) {
    //on retire le texte 'carte-' du nom de la carte pour n'avoir que le numéro afin de pouvoir le traiter en base de donnée
    numero = carte.getAttribute('name').replace('carte-','');
    $.ajax({
        url : 'ajax.php?numero='+numero // Envoi le numéro de la carte à la ressource ciblée (ajax.php)
    });
}
