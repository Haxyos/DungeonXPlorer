<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de personnage - Dungeon Xplorer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
        }
    </style>
</head>

<body class="text-gray-100">

    <?php
    include "../php/components/header.php";
    include "../php/Database.php";
    require '../Classe/Warrior.php';
    require '../Classe/Stealer.php';
    require '../Classe/Wizard.php';

    // Récupérer tous les sorts disponibles pour le sorcier
    $spells = [];
    try {
        $spellQuery = $db->query("SELECT id, nom, cout_mana FROM Spell ORDER BY cout_mana ASC");
        $spells = $spellQuery->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Ignorer si la table n'existe pas encore
    }

    if (isset($_POST["characterName"]) && isset($_POST["class"]) && isset($_POST["descChar"]) && isset($_POST["initiative"])) {
        $nom = trim($_POST["characterName"]);
        $class = strtolower($_POST["class"]);
        $descriptif = trim($_POST["descChar"]);
        $initiative = intval($_POST["initiative"]);
        $img = 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero';

        // Définir les équipements selon la classe
        switch ($class) {
            case "warrior":
            case "guerrier":
                $arme = 10;      // Épée courte
                $armure = 40;    // Armure de cuir
                $bouclier = 30;  // Bouclier en bois
                break;
            case "wizard":
            case "sorcier":
                $arme = 14;      // Bâton du sage
                $armure = 43;    // Robe de mage
                $bouclier = 0;   // Pas de bouclier
                // Vérifier que 3 sorts sont sélectionnés
                if (!isset($_POST['spell1']) || !isset($_POST['spell2']) || !isset($_POST['spell3'])) {
                    $errorMessage = "Le sorcier doit choisir 3 sorts !";
                    goto skip_creation;
                }
                $spell1 = intval($_POST['spell1']);
                $spell2 = intval($_POST['spell2']);
                $spell3 = intval($_POST['spell3']);
                
                // Vérifier que les 3 sorts sont différents
                if ($spell1 == $spell2 || $spell1 == $spell3 || $spell2 == $spell3) {
                    $errorMessage = "Vous devez choisir 3 sorts différents !";
                    goto skip_creation;
                }
                break;
            case "stealer":
            case "voleur":
                $arme = 13;      // Dague empoisonnée
                $armure = 44;    // Tunique de voleur
                $bouclier = 0;   // Pas de bouclier
                break;
            default:
                $errorMessage = "Classe invalide !";
                goto skip_creation;
        }

        try {
            // Commencer une transaction pour tout insérer ensemble
            $db->beginTransaction();

            $stmt = $db->prepare('INSERT INTO Hero (name, class_id, image, biography, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) 
                              VALUES (:name, :class_id, :image, :biography, :pv, :mana, :strength, :initiative, :armor, :weapon, :shield, :xp, :level, :user_id)');

            switch ($class) {
                case "warrior":
                case "guerrier":
                    $warrior = new Warrior();
                    $warrior->constructeurAvecParam($nom, $class, $img, $descriptif, $initiative, $armure, $arme, $bouclier);
                    $stmt->execute([
                        'name' => $warrior->getName(),
                        'class_id' => 1,
                        'image' => $warrior->getImSrc(),
                        'biography' => $warrior->getBiography(),
                        'pv' => $warrior->getHealth(),
                        'mana' => $warrior->getMana(),
                        'strength' => $warrior->getStrength(),
                        'initiative' => $warrior->getInitiative(),
                        'armor' => ($warrior->getArmorItem() > 0) ? $warrior->getArmorItem() : null,
                        'weapon' => ($warrior->getPrimaryWeapon() > 0) ? $warrior->getPrimaryWeapon() : null,
                        'shield' => ($warrior->getShield() > 0) ? $warrior->getShield() : null,
                        'xp' => $warrior->getExp(),
                        'level' => $warrior->getLevel(),
                        'user_id' => $_SESSION['user_id']
                    ]);
                    $heroId = $db->lastInsertId();
                    $successMessage = "✓ Guerrier créé avec succès ! Équipé d'une épée courte, d'une armure de cuir et d'un bouclier en bois.";
                    break;

                case "wizard":
                case "sorcier":
                    $wizard = new Wizard();
                    $wizard->constructeurAvecParam($nom, $class, $img, $descriptif, $initiative, $armure, $arme);
                    $stmt->execute([
                        'name' => $wizard->getName(),
                        'class_id' => 2,
                        'image' => $wizard->getImSrc(),
                        'biography' => $wizard->getBiography(),
                        'pv' => $wizard->getHealth(),
                        'mana' => $wizard->getMana(),
                        'strength' => $wizard->getStrength(),
                        'initiative' => $wizard->getInitiative(),
                        'armor' => ($wizard->getArmorItem() > 0) ? $wizard->getArmorItem() : null,
                        'weapon' => ($wizard->getPrimaryWeapon() > 0) ? $wizard->getPrimaryWeapon() : null,
                        'shield' => null,
                        'xp' => $wizard->getExp(),
                        'level' => $wizard->getLevel(),
                        'user_id' => $_SESSION['user_id']
                    ]);
                    $heroId = $db->lastInsertId();
                    
                    // Ajouter les 3 sorts dans la table Pouvoir
                    $spellStmt = $db->prepare('INSERT INTO Pouvoir (id_heros, id_spell) VALUES (:hero_id, :spell_id)');
                    $spellStmt->execute(['hero_id' => $heroId, 'spell_id' => $spell1]);
                    $spellStmt->execute(['hero_id' => $heroId, 'spell_id' => $spell2]);
                    $spellStmt->execute(['hero_id' => $heroId, 'spell_id' => $spell3]);
                    
                    $successMessage = "✓ Mage créé avec succès ! Équipé d'un bâton du sage et d'une robe de mage, avec 3 sorts maîtrisés.";
                    break;

                case "stealer":
                case "voleur":
                    $stealer = new Stealer();
                    $stealer->constructeurAvecParam($nom, $class, $img, $descriptif, $initiative, $armure, $arme);
                    $stmt->execute([
                        'name' => $stealer->getName(),
                        'class_id' => 3,
                        'image' => $stealer->getImSrc(),
                        'biography' => $stealer->getBiography(),
                        'pv' => $stealer->getHealth(),
                        'mana' => $stealer->getMana(),
                        'strength' => $stealer->getStrength(),
                        'initiative' => $stealer->getInitiative(),
                        'armor' => ($stealer->getArmorItem() > 0) ? $stealer->getArmorItem() : null,
                        'weapon' => ($stealer->getPrimaryWeapon() > 0) ? $stealer->getPrimaryWeapon() : null,
                        'shield' => null,
                        'xp' => $stealer->getExp(),
                        'level' => $stealer->getLevel(),
                        'user_id' => $_SESSION['user_id']
                    ]);
                    $heroId = $db->lastInsertId();
                    $successMessage = "✓ Voleur créé avec succès ! Équipé d'une dague empoisonnée et d'une tunique de voleur.";
                    break;
            }

            // Valider la transaction
            $db->commit();

        } catch (PDOException $e) {
            // Annuler en cas d'erreur
            $db->rollBack();
            $errorMessage = "Erreur : " . $e->getMessage();
        }
        
        skip_creation:
    }
    ?>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-5xl font-bold mb-2">
                <span class="text-yellow-500">NOUVELLE</span>
                <span class="text-white">AVENTURE</span>
            </h1>
            <p class="text-gray-400 italic">Créez votre héros et plongez dans l'univers défini par votre Maître du Jeu.
            </p>
        </div>

        <?php if (isset($_SESSION['username'])): ?>
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-6 text-center">
                <p class="text-gray-300">Connecté en tant que :
                    <span class="text-yellow-500 font-semibold"><?= htmlspecialchars($_SESSION['username']) ?></span>
                </p>
            </div>
        <?php endif; ?>

        <!-- Messages de succès/erreur -->
        <?php if (isset($successMessage)): ?>
            <div class="bg-green-900 border border-green-600 text-green-200 px-6 py-4 rounded-lg mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-bold"><?= $successMessage ?></p>
                    <p class="text-sm">Tu peux maintenant jouer avec ton personnage !</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <div class="bg-red-900 border border-red-600 text-red-200 px-6 py-4 rounded-lg mb-6">
                <p class="font-bold">✗ <?= $errorMessage ?></p>
            </div>
        <?php endif; ?>

        <!-- Formulaire principal -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl p-8 mb-6">
            <form method="POST" class="space-y-6" id="characterForm">
                <!-- Nom du personnage -->
                <div>
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        📜 Nom du personnage
                    </label>
                    <input type="text" name="characterName" required placeholder="Entrez le nom de votre héros"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition" required>
                </div>

                <!-- Classe -->
                <div>
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        ⚔️ Classe
                    </label>
                    <select name="class" id="classSelect" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition cursor-pointer">
                        <option value="warrior">🛡️ Guerrier - Tank puissant (10 PV, 0 Mana)</option>
                        <option value="wizard">🔮 Sorcier - Maître des arcanes (6 PV, 4 Mana)</option>
                        <option value="stealer">🗡️ Voleur - Agile et discret (8 PV, 0 Mana)</option>
                    </select>
                </div>

                <!-- Équipement automatique -->
                <div id="equipmentInfo" class="bg-gray-900 border border-gray-600 rounded-lg p-4">
                    <h3 class="text-yellow-500 font-semibold mb-3 text-lg">🎒 Équipement de départ</h3>
                    <div id="equipmentDetails" class="text-gray-300 space-y-2">
                        <!-- Rempli dynamiquement par JavaScript -->
                    </div>
                </div>

                <!-- Sorts (sorcier uniquement) -->
                <div id="spellsDiv" style="display: none;">
                    <h3 class="text-yellow-500 font-semibold mb-3 text-lg">✨ Choisissez 3 sorts</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-300 mb-2">Premier sort :</label>
                            <select name="spell1" 
                                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition cursor-pointer">
                                <option value="">-- Choisir un sort --</option>
                                <?php foreach ($spells as $spell): ?>
                                    <option value="<?= $spell['id'] ?>">
                                        <?= htmlspecialchars($spell['nom']) ?> (<?= $spell['cout_mana'] ?> mana)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-300 mb-2">Deuxième sort :</label>
                            <select name="spell2" 
                                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition cursor-pointer">
                                <option value="">-- Choisir un sort --</option>
                                <?php foreach ($spells as $spell): ?>
                                    <option value="<?= $spell['id'] ?>">
                                        <?= htmlspecialchars($spell['nom']) ?> (<?= $spell['cout_mana'] ?> mana)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-300 mb-2">Troisième sort :</label>
                            <select name="spell3" 
                                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition cursor-pointer">
                                <option value="">-- Choisir un sort --</option>
                                <?php foreach ($spells as $spell): ?>
                                    <option value="<?= $spell['id'] ?>">
                                        <?= htmlspecialchars($spell['nom']) ?> (<?= $spell['cout_mana'] ?> mana)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Histoire -->
                <div>
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        📖 Histoire du personnage
                    </label>
                    <textarea name="descChar" rows="4" required placeholder="Racontez l'histoire de votre héros..."
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition resize-none"></textarea>
                </div>

                <!-- Initiative -->
                <div>
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        ⚡ Initiative (bonus)
                    </label>
                    <input type="number" name="initiative" id="initiativeInput" value="0" min="0" max="10" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition">
                    <p class="text-gray-500 text-sm mt-1" id="initiativeHelp">Entre 0 et 10 - Détermine qui attaque en premier</p>
                </div>

                <!-- Bouton Submit -->
                <button type="submit" name="create"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-4 px-6 rounded-lg transition transform hover:scale-105 active:scale-95 shadow-lg text-lg uppercase tracking-wide">
                    🎭 Créer mon personnage
                </button>
            </form>
        </div>

        <!-- Retour à l'accueil -->
        <div class="text-center mt-8">
            <a href="/index.php" class="inline-block text-gray-400 hover:text-yellow-500 transition underline">
                ← Retour à l'accueil
            </a>
        </div>
    </div>

    <script>
        const classSelect = document.getElementById('classSelect');
        const equipmentDetails = document.getElementById('equipmentDetails');
        const spellsDiv = document.getElementById('spellsDiv');
        const initiativeInput = document.getElementById('initiativeInput');
        const initiativeHelp = document.getElementById('initiativeHelp');

        const equipmentByClass = {
            'warrior': {
                weapon: '⚔️ Épée courte (+2 Force)',
                armor: '🛡️ Armure de cuir (+3 PV)',
                shield: '🛡️ Bouclier en bois (+2 PV)'
            },
            'wizard': {
                weapon: '🪄 Bâton du sage (+1 Force, +5 Mana)',
                armor: '👘 Robe de mage (+2 PV, +3 Mana)',
                shield: null
            },
            'stealer': {
                weapon: '🗡️ Dague empoisonnée (+3 Force)',
                armor: '🥷 Tunique de voleur (+2 PV, +2 Initiative)',
                shield: null
            }
        };

        function updateEquipment() {
            const selectedClass = classSelect.value;
            const equipment = equipmentByClass[selectedClass];
            
            let html = `<p class="flex items-center"><span class="mr-2">•</span> ${equipment.weapon}</p>`;
            html += `<p class="flex items-center"><span class="mr-2">•</span> ${equipment.armor}</p>`;
            if (equipment.shield) {
                html += `<p class="flex items-center"><span class="mr-2">•</span> ${equipment.shield}</p>`;
            }
            
            equipmentDetails.innerHTML = html;

            // Afficher les sorts uniquement pour le sorcier
            if (selectedClass === 'wizard') {
                spellsDiv.style.display = 'block';
                document.querySelector('select[name="spell1"]').required = true;
                document.querySelector('select[name="spell2"]').required = true;
                document.querySelector('select[name="spell3"]').required = true;
            } else {
                spellsDiv.style.display = 'none';
                document.querySelector('select[name="spell1"]').required = false;
                document.querySelector('select[name="spell2"]').required = false;
                document.querySelector('select[name="spell3"]').required = false;
            }

            // Modifier les limites d'initiative pour le voleur
            if (selectedClass === 'stealer') {
                initiativeInput.min = 10;
                initiativeInput.max = 20;
                initiativeInput.value = 10;
                initiativeHelp.textContent = 'Entre 10 et 20 - Le voleur est naturellement agile !';
            } else {
                initiativeInput.min = 0;
                initiativeInput.max = 10;
                initiativeInput.value = 0;
                initiativeHelp.textContent = 'Entre 0 et 10 - Détermine qui attaque en premier';
            }
        }

        classSelect.addEventListener('change', updateEquipment);
        
        // Initialisation
        updateEquipment();
    </script>

</body>

</html>