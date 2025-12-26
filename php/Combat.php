<?php

function tourCombat($attaquant, $defenseur) {
    $initiativeAttaquant = rand(1, 6) + $attaquant->getInitiative();
    $initiativeDefenseur = rand(1, 6) + $defenseur->getInitiative();
    
    if($initiativeAttaquant == $initiativeDefenseur &&  ($attaquant->getClasse() != 'Monstre' && $attaquant->getClasse() != 'Voleur')){
        echo "<p class='text-yellow-400'>Égalité d'initiative! Les deux combattants se préparent...</p>";
        return;
    }
    
    if ($initiativeAttaquant >= $initiativeDefenseur) {
        $degats = 0; 
        $choix = $attaquant->choisirAction();
        
        if ($choix === 'physique' || $attaquant->getClasse() == 'Monstre') {
            $attaque = rand(1, 6) + $attaquant->getStrength();
            
            $arme = $attaquant->getPrimaryWeapon();
            if ($arme && isset($arme->bonus)) {
                $attaque += $arme->bonus;
            }
            
            $defense = rand(1, 6) + (int)($defenseur->getStrength() / 2);
            
            $armure = $defenseur->getArmorItem();
            if ($armure && isset($armure->bonus)) {
                $defense += $armure->bonus;
            }
            
            $degats = max(0, $attaque - $defense);
            $defenseur->takeDamage($degats);
            
            echo "<p class='text-green-400'>{$attaquant->getName()} attaque {$defenseur->getName()} et inflige {$degats} dégâts!</p>";
            
        } 
        elseif ($choix === 'magie' && $attaquant->getClasse() == 'Magicien') {
            $sort = $attaquant->choisirSort();
            
            if ($sort && isset($sort->cout_mana) && $sort->cout_mana <= $attaquant->getMana()) {
                $attaque_magique = (rand(1, 6) + rand(1, 6)) + $sort->cout_mana;
                $attaquant->setMana($attaquant->getMana() - $sort->cout_mana);
                
                $defense = rand(1, 6) + (int)($defenseur->getStrength() / 3);
                
                $armure = $defenseur->getArmorItem();
                if ($armure && isset($armure->bonus)) {
                    $defense += (int)($armure->bonus / 2); 
                }
                
                $degats = max(0, $attaque_magique - $defense);
                $defenseur->takeDamage($degats);
                
                echo "<p class='text-purple-400'>{$attaquant->getName()} lance {$sort->nom} et inflige {$degats} dégâts magiques!</p>";
            }
            else {
                echo "<p class='text-yellow-400'>{$attaquant->getName()} n'a pas assez de mana!</p>";
                return tourCombat($attaquant, $defenseur); 
            }
        } 
        elseif ($choix === 'potion') {
            if ($attaquant->getNombrePotionsPv() > 0) {
                $ancienPv = $attaquant->getHealth();
                $potionValeur = 5;
                $attaquant->setHealth(min($attaquant->getHealth() + $potionValeur, $attaquant->getPvMax()));
                $pvRecuperes = $attaquant->getHealth() - $ancienPv;
                $attaquant->setNombrePotionsPv($attaquant->getNombrePotionsPv() - 1);
                
                echo "<p class='text-green-400'>{$attaquant->getName()} boit une potion de vie et récupère {$pvRecuperes} PV!</p>";
            }
            else {
                echo "<p class='text-yellow-400'>{$attaquant->getName()} n'a plus de potions!</p>";
            }
        }
    } 
    else {
        echo "<p class='text-blue-400'>{$defenseur->getName()} est plus rapide et attaque en premier!</p>";
        
        $attaque = rand(1, 6) + $defenseur->getStrength();
        
        $arme = $defenseur->getPrimaryWeapon();
        if ($arme && isset($arme->bonus)) {
            $attaque += $arme->bonus;
        }
        
        $defense = rand(1, 6) + (int)($attaquant->getStrength() / 2);
        
        $armure = $attaquant->getArmorItem();
        if ($armure && isset($armure->bonus)) {
            $defense += $armure->bonus;
        }
        
        $degats = max(0, $attaque - $defense);
        $attaquant->takeDamage($degats);
        
        echo "<p class='text-red-400'>{$defenseur->getName()} contre-attaque et inflige {$degats} dégâts à {$attaquant->getName()}!</p>";
    }
}
?>