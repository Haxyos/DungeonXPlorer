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

    if (isset($_POST["characterName"]) && isset($_POST["class"]) && isset($_POST["descChar"]) && isset($_POST["initiative"]) && isset($_POST["arme"]) && isset($_POST["armure"])) {
        $nom = trim($_POST["characterName"]);
        $class = strtolower($_POST["class"]);
        $descriptif = trim($_POST["descChar"]);
        $initiative = intval($_POST["initiative"]);
        $arme = intval($_POST["arme"]);
        $armure = intval($_POST["armure"]);
        $bouclier = isset($_POST["bouclier"]) ? intval($_POST["bouclier"]) : 0;
        $img = 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero';

        try {
            $db->beginTransaction();

            $stmt = $db->prepare('INSERT INTO Hero (name, class_id, image, biography, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) 
                              VALUES (:name, :class_id, :image, :biography, :pv, :mana, :strength, :initiative, :armor, :weapon, :shield, :xp, :level, :user_id)');

            switch ($class) {
                case "warrior":
                case "guerrier":
                    $warrior = new Warrior($nom, $class, $img, $descriptif, $initiative, $armure, $arme, $bouclier);
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
                    
                    // Ajouter 3 potions de soin dans l'inventaire
                    $potionStmt = $db->prepare('INSERT INTO Inventory (hero_id, item_id, quantity) VALUES (:hero_id, 50, 3)');
                    $potionStmt->execute(['hero_id' => $heroId]);
                    
                    $successMessage = "✓ Guerrier créé avec succès !";
                    break;

                case "stealer":
                case "voleur":
                    $stealer = new Stealer($nom, $class, $img, $descriptif, $initiative, $armure, $arme);
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
                    
                    // Ajouter 3 potions de soin dans l'inventaire
                    $potionStmt = $db->prepare('INSERT INTO Inventory (hero_id, item_id, quantity) VALUES (:hero_id, 50, 3)');
                    $potionStmt->execute(['hero_id' => $heroId]);
                    
                    $successMessage = "✓ Voleur créé avec succès !";
                    break;

                default:
                    $errorMessage = "Classe invalide !";
                    $db->rollBack();
                    goto skip_creation;
            }

            $db->commit();

        } catch (PDOException $e) {
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
            <form method="POST" class="space-y-6">
                <!-- Nom du personnage -->
                <div>
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        📜 Nom du personnage
                    </label>
                    <input type="text" name="characterName" required placeholder="Entrez le nom de votre héros"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition">
                </div>

                <!-- Classe -->
                <div>
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        ⚔️ Classe
                    </label>
                    <select name="class" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition cursor-pointer">
                        <option value="warrior">🛡️ Guerrier - Tank puissant (10 PV, 0 Mana)</option>
                        <option value="stealer">🗡️ Voleur - Agile et discret (8 PV, 0 Mana)</option>
                    </select>
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
                    <input type="number" name="initiative" value="0" min="0" max="10" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition">
                    <p class="text-gray-500 text-sm mt-1">Entre 0 et 10 - Détermine qui attaque en premier</p>
                </div>

                <!-- Arme -->
                <div>
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        ⚔️ Arme de départ
                    </label>
                    <select name="arme" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition cursor-pointer">
                        <option value="2">Sans arme</option>
                        <option value="10">Épée courte (+2 Force)</option>
                        <option value="13">Dague empoisonnée (+3 Force)</option>
                    </select>
                </div>

                <!-- Armure -->
                <div>
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        🛡️ Armure de départ
                    </label>
                    <select name="armure" required
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition cursor-pointer">
                        <option value="1">Sans armure</option>
                        <option value="40">Armure de cuir (+3 PV)</option>
                        <option value="44">Tunique de voleur (+2 PV, +2 Initiative)</option>
                    </select>
                </div>

                <!-- Bouclier -->
                <div id="shield-div">
                    <label class="block text-yellow-500 font-semibold mb-2 text-lg">
                        🛡️ Bouclier (Guerrier seulement)
                    </label>
                    <select name="bouclier"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500 transition cursor-pointer">
                        <option value="3">Sans bouclier</option>
                        <option value="30">Bouclier en bois (+2 PV)</option>
                        <option value="31">Bouclier en fer (+4 PV)</option>
                    </select>
                </div>

                <!-- Bouton Submit -->
                <button type="submit" name="create"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-4 px-6 rounded-lg transition transform hover:scale-105 active:scale-95 shadow-lg text-lg uppercase tracking-wide">
                    🎭 Créer mon personnage
                </button>
            </form>
        </div>

        <!-- Séparateur -->
        <div class="flex items-center my-8">
            <div class="flex-1 border-t border-gray-700"></div>
            <span class="px-4 text-gray-500 text-sm uppercase tracking-wider">ou</span>
            <div class="flex-1 border-t border-gray-700"></div>
        </div>

        <!-- Bouton personnage par défaut -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-6 text-center">
            <h3 class="text-xl font-bold text-white mb-3">Test rapide</h3>
            <p class="text-gray-400 mb-4">Créez un personnage de test pour découvrir le jeu rapidement</p>
            <form method="POST">
                <button type="submit" name="default"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-8 rounded-lg transition transform hover:scale-105 active:scale-95 uppercase tracking-wide">
                    ⚡ Personnage par défaut
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
        // Cache le champ bouclier si ce n'est pas un guerrier
        const classSelect = document.querySelector('select[name="class"]');
        const shieldDiv = document.getElementById('shield-div');

        classSelect.addEventListener('change', function () {
            if (this.value === 'warrior' || this.value === 'guerrier') {
                shieldDiv.style.display = 'block';
            } else {
                shieldDiv.style.display = 'none';
            }
        });

        // Initialisation au chargement
        if (classSelect.value !== 'warrior') {
            shieldDiv.style.display = 'none';
        }
    </script>

</body>

</html>