<?php
include_once 'Database.php';
// Select dans la base pour créer les deux personnages 
function chercheMonstre($id_chapitre){
    global $db;
    $valeurMonstre = $db -> prepare ("SELECT pv,mana,initiative,strength, FROM monstres WHERE id_chapitre = :id_chapitre");
    $valeurMonstre -> execute (['id_chapitre' => $id_chapitre]);
    $monstre = new Monster();
    return $monstre;
}

function tourCombat($attaquant,$defenseur) {
    $initiativeAttaquant = rand(1, 6) + $attaquant->initiative;
    $initiativeDefenseur = rand(1, 6) + $defenseur->initiative;
    // Détermination de l'attaquant
    if ($initiativeAttaquant > $initiativeDefenseur || $attaquant->classe == 'Voleur') {
        // Choix de l’action par l'attaquant
        $choix = $attaquant->choisirAction(); // 'physique', 'magie', ou 'potion'
        if ($choix === 'physique') {
        // Attaque physique
            $attaque = rand(1, 6) + $attaquant->force + $attaquant->arme->bonus;
            $defense = rand(1, 6) + (int)($defenseur->force / 2) + $defenseur->armure->bonus;
            $degats = max(0, $attaque - $defense);
            $defenseur->pv -= $degats;
        } 
        elseif ( $attaquant -> classe = 'Monstre' || $choix === 'magie' && $attaquant->classe == 'Magicien') {
        // Attaque magique
            $sort = $attaquant -> choisirSort();
            if ($sort -> cout_sort < $attaquant){
                $attaque_magique = (rand(1, 6) + rand(1, 6)) + $sort -> cout_sort;
                $attaquant->mana -= $sort -> cout_sort;
                $defense = rand(1, 6) + (int)($defenseur->force / 2) + $defenseur->armure->bonus;
                $degats = max(0, $attaque_magique - $defense);
            }
            else {
                return tourCombat($attaquant,$defenseur);
            }
        } 
        elseif ($choix === 'potion') {
        // Boire une potion
            $potion = $attaquant->choisirPotion();
            if ($potion->type === 'pv' && $attaquant->nombre_potions_pv > 0) {
                $attaquant->pv = min($attaquant->pv + $potion->valeur, $attaquant->pv_max);
            }
            elseif ($potion->type === 'mana' && $attaquant->nombre_potions_mana > 0) {
                $attaquant->mana = min($attaquant->mana + $potion->valeur, $attaquant->mana_max);
            }
        }
    }
    // Boucle jusqu'à ce qu'un protagoniste meure
    return $attaquant->pv > 0 && $defenseur->pv > 0 ? tourCombat($defenseur, $attaquant) :
    "Fin du combat";
}
?>