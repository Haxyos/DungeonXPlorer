<?php
require_once '../../Controller/ChapterController.php';
require_once '../../php/Database.php';
require_once '../../Classe/Warrior.php';
require_once '../../Classe/Wizard.php';
require_once '../../Classe/Stealer.php';
require_once '../../php/Combat.php';
require_once '../../php/Monster.php';

$userId = $_SESSION['user_id'] ?? '';
$heroId = $_GET["hero"] ?? '';

// FIX : Gérer la fin du combat AVANT tout output HTML
if(isset($_GET['end_combat']) && $_GET['end_combat'] == 1) {
    $combatSessionKey = 'combat_' . $heroId;
    
    // Récupérer le chapitre actuel depuis la session de combat
    if(isset($_SESSION[$combatSessionKey]['chapter_id'])) {
        $currentChapterId = $_SESSION[$combatSessionKey]['chapter_id'];
        
        // Marquer le combat comme terminé en retirant le monstre du chapitre pour ce héros
        // On va créer une table temporaire en session pour tracker les combats terminés
        if(!isset($_SESSION['completed_combats'])) {
            $_SESSION['completed_combats'] = [];
        }
        $_SESSION['completed_combats'][$heroId][$currentChapterId] = true;
    }
    
    // Supprimer uniquement la session de combat de CE héros
    if(isset($_SESSION[$combatSessionKey])) {
        unset($_SESSION[$combatSessionKey]);
    }
    
    // Rediriger vers la page du chapitre
    header("Location: index.php?hero=" . $heroId);
    exit;
}

if (!$userId || !$heroId) {
    header("Location: /index.php");
    exit;
}

$req = $db->prepare("SELECT * FROM Hero_Progress JOIN Hero ON Hero.id = Hero_Progress.hero_id WHERE Hero.user_id = ? AND hero_id = ?");
$req->execute([$userId, $heroId]);
$userHero = $req->fetch();

if (!$userHero) {
    header("Location: /index.php");
    exit;
}

$chapterId = $userHero["chapter_id"];

$chapterController = new ChapterController();
$chapter = $chapterController->getChapter($chapterId);

if (!$chapter) {
    header("Location: /index.php?error=chapter_not_found");
    exit;
}

// NOUVEAU : Récupérer le nombre de potions depuis la table Inventory
$stmtPotion = $db->prepare("SELECT quantity FROM Inventory WHERE hero_id = :hero_id AND item_id = 50");
$stmtPotion->execute(['hero_id' => $heroId]);
$potionData = $stmtPotion->fetch();
$nombrePotions = $potionData ? (int)$potionData['quantity'] : 0;

if(isset($userHero['class_id'])){
    if($userHero['class_id'] == 1) {
        $heroCaracter = new Warrior();
        $heroCaracter->constructeurPréfait($userHero['id']);
    }
    if($userHero['class_id'] == 2) {
        $heroCaracter = new Wizard();
        $heroCaracter->constructeurPréfait($userHero['id']);
    }
    if($userHero['class_id'] == 3) {
        $heroCaracter = new Stealer();
        $heroCaracter->constructeurPréfait($userHero['id']);
    }
    
    // NOUVEAU : Synchroniser le nombre de potions du héros avec l'inventory
    $heroCaracter->setNombrePotionsPv($nombrePotions);
}

$monster = null;
$combatEnCours = false;

// FIX 1: Utiliser une clé de session unique par héros
$combatSessionKey = 'combat_' . $heroId;

if(isset($chapterId)){
    $stmt = $db->prepare("SELECT monster_id FROM Chapter WHERE id = :id");
    $stmt->execute(['id' => $chapterId]);
    $value = $stmt->fetch();
    $monsterId = $value['monster_id'] ?? 0;

    // Vérifier si le combat a déjà été terminé pour ce héros sur ce chapitre
    $combatCompleted = isset($_SESSION['completed_combats'][$heroId][$chapterId]);

    if($monsterId != 0 && !$combatCompleted){
        // FIX 2: Vérifier si c'est un nouveau combat ou une reprise
        if(!isset($_SESSION[$combatSessionKey])) {
            $stmt = $db->prepare("SELECT name, pv, mana, xp, strength FROM Monster WHERE id = :id_monstre");
            $stmt->execute(['id_monstre' => $monsterId]);
            $values = $stmt->fetch();
            
            $_SESSION[$combatSessionKey] = [
                'monster_name' => $values['name'],
                'monster_health' => $values['pv'],
                'monster_max_health' => $values['pv'],
                'monster_mana' => $values['mana'],
                'monster_xp' => $values['xp'],
                'monster_strength' => $values['strength'],
                'combat_log' => [],
                'hero_id' => $heroId,
                'chapter_id' => $chapterId // Ajouter l'ID du chapitre
            ];
        }
        
        $combatEnCours = true;
        $combatData = $_SESSION[$combatSessionKey];
        
        $monster = new Monster(
            $combatData['monster_name'], 
            $combatData['monster_health'], 
            $combatData['monster_mana'], 
            $combatData['monster_xp']
        );
        $monster->setStrength($combatData['monster_strength']);
    }    
}

$combatLog = [];
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['combat_action']) && $combatEnCours) {
    
    $action = $_POST['combat_action'];
    
    $initiativeJoueur = rand(1, 6) + $heroCaracter->getInitiative();
    $initiativeMonstre = rand(1, 6) + $monster->getInitiative();
    
    if($initiativeJoueur >= $initiativeMonstre) {
        $logJoueur = executerActionJoueur($action, $heroCaracter, $monster, $db, $heroId);
        $combatLog[] = $logJoueur;
        
        // MODIFIÉ : Ne plus mettre à jour nombre_potions_pv dans Hero
        $stmt = $db->prepare("UPDATE Hero SET pv = :pv, mana = :mana WHERE id = :id");
        $stmt->execute([
            'pv' => $heroCaracter->getHealth(),
            'mana' => $heroCaracter->getMana(),
            'id' => $heroId
        ]);
        
        if($monster->getHealth() > 0) {
            $logMonstre = executerActionMonstre($heroCaracter, $monster);
            $combatLog[] = $logMonstre;
            
            $stmt = $db->prepare("UPDATE Hero SET pv = :pv WHERE id = :id");
            $stmt->execute([
                'pv' => $heroCaracter->getHealth(),
                'id' => $heroId
            ]);
        }
    } else {
        $combatLog[] = "<p class='text-yellow-400'>⚡ Le monstre est plus rapide!</p>";
        $logMonstre = executerActionMonstre($heroCaracter, $monster);
        $combatLog[] = $logMonstre;
        
        $stmt = $db->prepare("UPDATE Hero SET pv = :pv WHERE id = :id");
        $stmt->execute([
            'pv' => $heroCaracter->getHealth(),
            'id' => $heroId
        ]);
        
        if($heroCaracter->getHealth() > 0) {
            $logJoueur = executerActionJoueur($action, $heroCaracter, $monster, $db, $heroId);
            $combatLog[] = $logJoueur;
            
            // MODIFIÉ : Ne plus mettre à jour nombre_potions_pv dans Hero
            $stmt = $db->prepare("UPDATE Hero SET pv = :pv, mana = :mana WHERE id = :id");
            $stmt->execute([
                'pv' => $heroCaracter->getHealth(),
                'mana' => $heroCaracter->getMana(),
                'id' => $heroId
            ]);
        }
    }
    
    // FIX 3: Mettre à jour la session avec la clé spécifique au héros
    $_SESSION[$combatSessionKey]['monster_health'] = $monster->getHealth();
    $_SESSION[$combatSessionKey]['combat_log'] = array_merge($_SESSION[$combatSessionKey]['combat_log'], $combatLog);
    
    if($heroCaracter->getHealth() <= 0) {
        $_SESSION[$combatSessionKey]['combat_log'][] = "<p class='text-red-600 font-bold text-xl'>💀 Vous avez été vaincu...</p>";
        $_SESSION[$combatSessionKey]['status'] = 'defeat';
        
        // FIX: Marquer le chapitre 10 comme prochain chapitre en cas de défaite
        $stmt = $db->prepare("UPDATE Hero_Progress SET chapter_id = 10 WHERE hero_id = :hero_id AND user_id = :user_id");
        $stmt->execute([
            'hero_id' => $heroId,
            'user_id' => $userId
        ]);
        
    } elseif($monster->getHealth() <= 0) {
        $_SESSION[$combatSessionKey]['combat_log'][] = "<p class='text-green-600 font-bold text-xl'>🎉 Victoire! Vous gagnez {$_SESSION[$combatSessionKey]['monster_xp']} XP!</p>";
        
        $stmt = $db->prepare("UPDATE Hero SET xp = xp + :xp WHERE id = :id");
        $stmt->execute(['xp' => $_SESSION[$combatSessionKey]['monster_xp'], 'id' => $heroId]);
        
        $_SESSION[$combatSessionKey]['status'] = 'victory';
    }
    
    header("Location: " . $_SERVER['PHP_SELF'] . "?hero=" . $heroId);
    exit;
}

// MODIFIÉ : Fonction avec les paramètres $db et $heroId pour gérer l'inventory
function executerActionJoueur($action, &$hero, &$monster, $db, $heroId) {
    $message = "";
    
    switch ($action) {
        case 'attaque_physique':
            $attaque = rand(1, 6) + $hero->getStrength();
            $arme = $hero->getPrimaryWeapon();
            if ($arme && is_object($arme) && isset($arme->bonus)) {
                $attaque += $arme->bonus;
            }
            
            $defense = rand(1, 6) + (int)($monster->getStrength() / 2);
            $degats = max(0, $attaque - $defense);
            $monster->takeDamage($degats);
            
            $message = "<p class='text-green-400'>⚔️ Vous attaquez {$monster->getName()} et infligez <strong>{$degats} dégâts</strong>!</p>";
            break;
            
        case 'attaque_magique':
            if ($hero->getClasse() == 'Wizard' && $hero->getMana() >= 5) {
                $sort = $hero->choisirSort();
                $attaque_magique = (rand(1, 6) + rand(1, 6)) + $sort->degats_base;
                $hero->setMana($hero->getMana() - $sort->cout_mana);
                
                $defense = rand(1, 6) + (int)($monster->getStrength() / 3);
                $degats = max(0, $attaque_magique - $defense);
                $monster->takeDamage($degats);
                
                $message = "<p class='text-purple-400'>🔮 Vous lancez <strong>{$sort->nom}</strong> et infligez <strong>{$degats} dégâts magiques</strong>!</p>";
            } else {
                $message = "<p class='text-yellow-400'>⚠️ Pas assez de mana ou vous n'êtes pas magicien!</p>";
            }
            break;
            
        case 'potion':
            if ($hero->getNombrePotionsPv() > 0) {
                $ancienPv = $hero->getHealth();
                $hero->setHealth(min($hero->getHealth() + 20, $hero->getPvMax()));
                $pvRecuperes = $hero->getHealth() - $ancienPv;
                
                // NOUVEAU : Diminuer la quantité dans la table Inventory
                $hero->setNombrePotionsPv($hero->getNombrePotionsPv() - 1);
                
                // NOUVEAU : Mettre à jour l'inventory dans la BDD
                $nouveauNombrePotions = $hero->getNombrePotionsPv();
                
                if ($nouveauNombrePotions > 0) {
                    // Mettre à jour la quantité
                    $stmt = $db->prepare("UPDATE Inventory SET quantity = :quantity WHERE hero_id = :hero_id AND item_id = 50");
                    $stmt->execute([
                        'quantity' => $nouveauNombrePotions,
                        'hero_id' => $heroId
                    ]);
                } else {
                    // Supprimer l'entrée si quantité = 0
                    $stmt = $db->prepare("DELETE FROM Inventory WHERE hero_id = :hero_id AND item_id = 50");
                    $stmt->execute(['hero_id' => $heroId]);
                }
                
                $message = "<p class='text-green-400'>🧪 Vous buvez une potion et récupérez <strong>{$pvRecuperes} PV</strong>!</p>";
            } else {
                $message = "<p class='text-yellow-400'>⚠️ Vous n'avez plus de potions!</p>";
            }
            break;
    }
    
    return $message;
}

function executerActionMonstre(&$hero, &$monster) {
    $attaque = rand(1, 6) + $monster->getStrength();
    $defense = rand(1, 6) + (int)($hero->getStrength() / 2);
    
    $armure = $hero->getArmorItem();
    if ($armure && is_object($armure) && isset($armure->bonus)) {
        $defense += $armure->bonus;
    }
    
    $degats = max(0, $attaque - $defense);
    $hero->takeDamage($degats);
    
    return "<p class='text-red-400'>👹 {$monster->getName()} vous attaque et inflige <strong>{$degats} dégâts</strong>!</p>";
}

include_once '../../php/components/header.php';
?>

<main class="pt-32 pb-12 px-6 min-h-screen bg-[#1A1A1A] text-white font-sans">
    
    <?php if($combatEnCours): ?>
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-center mb-8 text-[#f2a900]">
                ⚔️ Combat contre <?php echo htmlspecialchars($combatData['monster_name']); ?>
            </h1>
            
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- Carte du héros -->
                <div class="bg-gradient-to-br from-green-900/40 to-gray-900 rounded-xl p-6 border border-green-700">
                    <h2 class="text-2xl font-bold text-green-400 mb-4">
                        🛡️ <?php echo htmlspecialchars($heroCaracter->getName()); ?>
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>PV</span>
                                <span class="font-bold"><?php echo $heroCaracter->getHealth(); ?> / <?php echo $heroCaracter->getPvMax(); ?></span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full transition-all" 
                                     style="width: <?php echo min(100, ($heroCaracter->getHealth() / max(1, $heroCaracter->getPvMax())) * 100); ?>%"></div>
                            </div>
                        </div>
                        
                        <?php if($heroCaracter->getClasse() == 'Wizard'): ?>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>Mana</span>
                                <span class="font-bold"><?php echo $heroCaracter->getMana(); ?> / <?php echo $heroCaracter->getManaMax(); ?></span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full transition-all" 
                                     style="width: <?php echo min(100, ($heroCaracter->getMana() / max(1, $heroCaracter->getManaMax())) * 100); ?>%"></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex justify-between text-sm">
                            <span>🧪 Potions:</span>
                            <span class="font-bold"><?php echo $heroCaracter->getNombrePotionsPv(); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Carte du monstre -->
                <div class="bg-gradient-to-br from-red-900/40 to-gray-900 rounded-xl p-6 border border-red-700">
                    <h2 class="text-2xl font-bold text-red-400 mb-4">
                        👹 <?php echo htmlspecialchars($combatData['monster_name']); ?>
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>PV</span>
                                <span class="font-bold"><?php echo $combatData['monster_health']; ?> / <?php echo $combatData['monster_max_health']; ?></span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-3">
                                <div class="bg-red-500 h-3 rounded-full transition-all" 
                                     style="width: <?php echo min(100, ($combatData['monster_health'] / max(1, $combatData['monster_max_health'])) * 100); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Journal de combat -->
            <div class="bg-gray-900/60 rounded-xl p-6 border border-gray-700 mb-8 max-h-64 overflow-y-auto">
                <h3 class="text-xl font-bold mb-4 text-[#f2a900]">📜 Journal de Combat</h3>
                <div class="space-y-2">
                    <?php if(empty($combatData['combat_log'])): ?>
                        <p class="text-gray-400 italic">Le combat commence...</p>
                    <?php else: ?>
                        <?php foreach($combatData['combat_log'] as $entry): ?>
                            <?php echo $entry; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions de combat ou fin de combat -->
            <?php if(!isset($_SESSION[$combatSessionKey]['status'])): ?>
            <div class="bg-gray-900/60 rounded-xl p-6 border border-gray-700">
                <h3 class="text-xl font-bold mb-4 text-[#f2a900]">⚔️ Choisissez votre action</h3>
                <form method="POST" class="grid md:grid-cols-3 gap-4">
                    <button type="submit" name="combat_action" value="attaque_physique"
                            class="bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-bold py-4 px-6 rounded-lg transition-all transform hover:scale-105">
                        ⚔️<br>Attaque Physique
                    </button>
                    
                    <?php if($heroCaracter->getClasse() == 'Wizard'): ?>
                    <button type="submit" name="combat_action" value="attaque_magique"
                            class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition-all transform hover:scale-105"
                            <?php echo $heroCaracter->getMana() < 5 ? 'disabled opacity-50' : ''; ?>>
                        🔮<br>Boule de feu (5 mana)
                    </button>
                    <?php endif; ?>
                    
                    <button type="submit" name="combat_action" value="potion"
                            class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-lg transition-all transform hover:scale-105"
                            <?php echo $heroCaracter->getNombrePotionsPv() <= 0 ? 'disabled opacity-50' : ''; ?>>
                        🧪<br>Potion (<?php echo $heroCaracter->getNombrePotionsPv(); ?>)
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="text-center">
                <?php if($_SESSION[$combatSessionKey]['status'] == 'victory'): ?>
                    <a href="?hero=<?php echo $heroId; ?>&end_combat=1" 
                       class="inline-block bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-4 px-8 rounded-lg transition-all transform hover:scale-105">
                        ✨ Continuer l'aventure
                    </a>
                <?php else: ?>
                    <a href="?hero=<?php echo $heroId; ?>&end_combat=1" 
                       class="inline-block bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold py-4 px-8 rounded-lg transition-all transform hover:scale-105">
                        💀 Revenir au village (Chapitre 10)
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
    <?php else: ?>
        <!-- Affichage du chapitre normal (sans combat) -->
        <div class="mb-6 bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg p-4 border border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#941515] rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Héros</p>
                    <p class="text-white font-bold"><?php echo htmlspecialchars($userHero['name'] ?? 'Aventurier'); ?></p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-400">Chapitre</p>
                <p class="text-white font-bold"><?php echo htmlspecialchars($chapterId); ?></p>
            </div>
        </div>

        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-4 text-center">
                <?php echo htmlspecialchars($chapter->getTitle()); ?>
            </h1>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-6 border border-gray-700 mb-8">
            <div class="text-gray-300 leading-relaxed text-lg whitespace-pre-line">
                <?php echo htmlspecialchars($chapter->getDescription()); ?>
            </div>
        </div>

        <?php if (!empty($chapter->getChoices())): ?>
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-6 border border-gray-700">
                <h2 class="text-2xl font-bold text-white mb-4">
                    <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                    </svg>
                    Choisissez votre chemin
                </h2>

                <div class="space-y-3">
                    <?php foreach ($chapter->getChoices() as $choice): ?>
                        <a href="process-choice.php?chapter=<?php echo htmlspecialchars($choice['chapter']); ?>&hero=<?php echo htmlspecialchars($heroId); ?>"
                            class="block bg-gray-700/50 hover:bg-[#941515] border border-gray-600 hover:border-[#941515] rounded-lg p-4 transition-all transform hover:-translate-y-1 hover:shadow-xl group">
                            <span class="text-white font-semibold text-lg flex items-center">
                                <svg class="w-5 h-5 mr-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                <?php echo htmlspecialchars($choice['text']); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include_once '../../php/components/footer.php'; ?>