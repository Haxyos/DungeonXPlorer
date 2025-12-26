<?php
session_start();

// 1. Sécurité & Inclusions
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?message=Veuillez vous connecter");
    exit();
}

include_once("../php/components/header.php");
require_once("../php/Database.php");

// Inclusion de tes classes (Assure-toi que le chemin est correct)
// Si les fichiers n'existent pas encore, commente ces lignes pour tester l'interface
require_once '../Classe/Warrior.php';
require_once '../Classe/Stealer.php';
require_once '../Classe/Wizard.php';

$userId = $_SESSION['user_id'];
$heroes = [];
$error = null;
$success = null;

// 2. Récupération des héros existants
try {
    $stmt = $db->prepare("SELECT * FROM Hero WHERE user_id = :user_id ORDER BY id DESC");
    $stmt->execute([':user_id' => $userId]);
    $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lecture héros : " . $e->getMessage();
}

// 3. Traitement du Formulaire de Création
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_hero'])) {
    
    // Récupération des données
    $nom = htmlspecialchars($_POST["characterName"]);
    $class = $_POST["class"]; // Warrior, Wizard, Stealer
    $descriptif = htmlspecialchars($_POST["descChar"]);
    $img = htmlspecialchars($_POST["image"]);
    
    // Stats issues des lancers de dés (inputs readonly)
    $initiative = intval($_POST["initiative"]);
    $arme = intval($_POST["arme"]);
    $armure = intval($_POST["armure"]);
    // Bouclier est optionnel
    $bouclier = isset($_POST["bouclier"]) ? intval($_POST["bouclier"]) : 0;

    // Image par défaut si vide
    if(empty($img)) {
        $img = 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=' . $class;
    }

    try {
        // Logique de création selon la classe
        switch ($class) {
            case "Warrior":
                // Instanciation (Adapte les arguments selon ton constructeur réel)
                $heroObj = new Warrior($nom, 1, $img, $descriptif, $initiative, $armure, $arme, $bouclier);
                // SQL pour Guerrier (Note: j'ai retiré 'id' car c'est auto-incrémenté généralement)
                $sql = 'INSERT INTO hero (name, class_id, image, biography, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) 
                        VALUES (:name, :class, :img, :bio, :pv, :mana, :str, :init, :armor, :weapon, :shield, :xp, :lvl, :uid)';
                break;

            case "Wizard":
                $heroObj = new Wizard($nom, 2, $img, $descriptif, $initiative, $armure, $arme);
                $sql = 'INSERT INTO hero (name, class_id, image, biography, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) 
                        VALUES (:name, :class, :img, :bio, :pv, :mana, :str, :init, :armor, :weapon, 0, :xp, :lvl, :uid)';
                break;

            case "Stealer":
                $heroObj = new Stealer($nom, 3, $img, $descriptif, $initiative, $armure, $arme);
                $sql = 'INSERT INTO hero (name, class_id, image, biography, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) 
                        VALUES (:name, :class, :img, :bio, :pv, :mana, :str, :init, :armor, :weapon, 0, :xp, :lvl, :uid)';
                break;
        }

        if (isset($heroObj) && isset($sql)) {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':name' => $heroObj->getName(),
                ':class' => $class == "Warrior" ? 1 : ($class == "Wizard" ? 2 : 3), // Conversion string -> int ID
                ':img' => $heroObj->getImSrc(), // ou $img direct
                ':bio' => $heroObj->getBiography(),
                ':pv' => $heroObj->getHealth(),
                ':mana' => $heroObj->getMana(),
                ':str' => $heroObj->getStrength(),
                ':init' => $heroObj->getInitiative(),
                ':armor' => $heroObj->getArmorItem(),
                ':weapon' => $heroObj->getPrimaryWeapon(),
                ':shield' => ($class == "Warrior") ? $heroObj->getShield() : 0,
                ':xp' => $heroObj->getExp(),
                ':lvl' => $heroObj->getLevel(),
                ':uid' => $_SESSION['user_id']
            ]);
            
            // Rafraichir la page
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

    } catch (Exception $e) {
        $error = "Erreur création : " . $e->getMessage();
    }
}
?>

<main class="min-h-screen bg-[#1A1A1A] font-sans text-white py-12 px-6 relative">

    <div class="max-w-7xl mx-auto mb-12 text-center border-b border-gray-800 pb-8 animate-fade-in-down">
        <h2 class="text-4xl md:text-5xl font-extrabold uppercase text-[#f2a900] mb-2 drop-shadow-lg">
            Guilde des Aventuriers
        </h2>
        
        <?php if($error): ?>
            <div class="mt-4 p-3 bg-red-900/80 border border-red-500 text-white rounded font-bold">
                <?= $error ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-24">
        
        <div onclick="toggleModal('modal-create')" class="cursor-pointer group relative h-96 border-2 border-dashed border-gray-700 rounded-xl flex flex-col items-center justify-center hover:border-[#f2a900] hover:bg-gray-900/50 transition-all duration-300">
            <div class="w-20 h-20 rounded-full bg-gray-800 group-hover:bg-[#f2a900] flex items-center justify-center transition-colors duration-300 mb-4 shadow-lg">
                <i class="fa-solid fa-plus text-3xl text-gray-400 group-hover:text-black"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-400 group-hover:text-white uppercase tracking-wider">Créer un Héros</h3>
        </div>

        <?php foreach ($heroes as $hero): ?>
        <div class="group relative bg-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-lg hover:shadow-[0_0_20px_rgba(242,169,0,0.3)] hover:-translate-y-2 transition-all duration-300">
            <div class="h-48 w-full bg-cover bg-center relative" style="background-image: url('<?= htmlspecialchars($hero['image']) ?>');">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                <div class="absolute top-2 right-2 bg-[#941515] text-white text-xs font-bold px-2 py-1 rounded border border-red-400">
                    Niv. <?= $hero['current_level'] ?>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-2xl font-bold text-[#f2a900] mb-1"><?= htmlspecialchars($hero['name']) ?></h3>
                <p class="text-xs text-gray-500 mb-4 uppercase tracking-widest">
                    <?php 
                        if($hero['class_id'] == 1) echo "Guerrier";
                        elseif($hero['class_id'] == 2) echo "Sorcier";
                        elseif($hero['class_id'] == 3) echo "Voleur";
                        else echo "Inconnu";
                    ?>
                </p>
                
                <div class="grid grid-cols-3 gap-2 text-center text-sm text-gray-300 mb-4 bg-gray-800 rounded p-2 border border-gray-700">
                    <div title="PV"><i class="fa-solid fa-heart text-red-600"></i> <?= $hero['pv'] ?></div>
                    <div title="Mana"><i class="fa-solid fa-bolt text-blue-400"></i> <?= $hero['mana'] ?></div>
                    <div title="Force"><i class="fa-solid fa-hand-fist text-orange-500"></i> <?= $hero['strength'] ?></div>
                </div>

                <a href="./play.php?id=<?= $hero['id'] ?>" class="block w-full py-2 text-center bg-[#f2a900] hover:bg-yellow-500 text-black font-bold rounded uppercase text-sm transition-colors shadow-md">
                    Jouer
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="modal-create" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-create')"></div>
        
        <div class="relative min-h-screen md:min-h-0 md:absolute md:top-10 md:left-1/2 md:transform md:-translate-x-1/2 w-full max-w-2xl bg-[#111] border-x-2 md:border-2 border-[#941515] rounded-none md:rounded-lg shadow-[0_0_50px_rgba(148,21,21,0.5)] p-6 md:p-8">
            
            <div class="flex justify-between items-center mb-6 border-b border-gray-800 pb-4">
                <h3 class="text-2xl font-bold text-white"><span class="text-[#f2a900]">Nouveau</span> Destin</h3>
                <button onclick="toggleModal('modal-create')" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <form action="" method="POST" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-[#f2a900] uppercase">Nom</label>
                            <input type="text" name="characterName" required class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:border-[#f2a900] focus:outline-none transition">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-[#f2a900] uppercase">Classe</label>
                            <select id="classSelect" name="class" onchange="updateClassFields()" class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:border-[#f2a900] focus:outline-none transition">
                                <option value="Warrior">Guerrier (Force brute)</option>
                                <option value="Wizard">Sorcier (Magie des arcanes)</option>
                                <option value="Stealer">Voleur (Ombres & Dagues)</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Avatar (URL)</label>
                            <input type="url" name="image" class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:border-[#f2a900] focus:outline-none transition text-sm" placeholder="https://...">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Histoire</label>
                        <textarea name="descChar" rows="8" class="w-full h-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:border-[#f2a900] focus:outline-none transition resize-none" placeholder="Écrivez votre légende..."></textarea>
                    </div>
                </div>

                <div class="bg-gray-900/50 p-4 rounded border border-gray-800">
                    <h4 class="text-[#f2a900] font-bold border-b border-gray-700 pb-2 mb-4"><i class="fa-solid fa-dice-d20 mr-2"></i>Déterminez votre destin</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-end gap-2">
                            <div class="flex-grow">
                                <label class="text-[10px] text-gray-400 uppercase">Initiative</label>
                                <input type="number" id="initInput" name="initiative" readonly class="w-full bg-[#111] border border-gray-700 text-white font-mono text-center rounded p-2" placeholder="?">
                            </div>
                            <button type="button" onclick="rollDice('initInput', 10)" class="bg-gray-700 hover:bg-[#f2a900] hover:text-black text-white p-2.5 rounded transition shadow-lg h-[42px] w-[42px] flex items-center justify-center">
                                <i class="fa-solid fa-dice"></i>
                            </button>
                        </div>

                        <div class="flex items-end gap-2">
                            <div class="flex-grow">
                                <label class="text-[10px] text-gray-400 uppercase">Dégâts Arme</label>
                                <input type="number" id="weaponInput" name="arme" readonly class="w-full bg-[#111] border border-gray-700 text-white font-mono text-center rounded p-2" placeholder="?">
                            </div>
                            <button type="button" onclick="rollDice('weaponInput', 20)" class="bg-gray-700 hover:bg-[#941515] hover:text-white text-white p-2.5 rounded transition shadow-lg h-[42px] w-[42px] flex items-center justify-center">
                                <i class="fa-solid fa-gavel"></i>
                            </button>
                        </div>

                        <div class="flex items-end gap-2">
                            <div class="flex-grow">
                                <label class="text-[10px] text-gray-400 uppercase">Solidité Armure</label>
                                <input type="number" id="armorInput" name="armure" readonly class="w-full bg-[#111] border border-gray-700 text-white font-mono text-center rounded p-2" placeholder="?">
                            </div>
                            <button type="button" onclick="rollDice('armorInput', 20)" class="bg-gray-700 hover:bg-blue-600 hover:text-white text-white p-2.5 rounded transition shadow-lg h-[42px] w-[42px] flex items-center justify-center">
                                <i class="fa-solid fa-shield-halved"></i>
                            </button>
                        </div>

                        <div id="shieldField" class="hidden flex items-end gap-2">
                            <div class="flex-grow">
                                <label class="text-[10px] text-gray-400 uppercase">Parade Bouclier</label>
                                <input type="number" id="shieldInput" name="bouclier" readonly class="w-full bg-[#111] border border-gray-700 text-white font-mono text-center rounded p-2" placeholder="?">
                            </div>
                            <button type="button" onclick="rollDice('shieldInput', 10)" class="bg-gray-700 hover:bg-orange-600 hover:text-white text-white p-2.5 rounded transition shadow-lg h-[42px] w-[42px] flex items-center justify-center">
                                <i class="fa-solid fa-shield"></i>
                            </button>
                        </div>

                    </div>

                    <div id="spellField" class="hidden mt-4 pt-4 border-t border-gray-700">
                        <label class="text-xs font-bold text-blue-400 uppercase mb-2 block">Grimoire de sorts</label>
                        <div class="grid grid-cols-3 gap-2">
                            <select name="spells[]" class="bg-[#111] border border-gray-700 text-xs text-white rounded p-2"><option>Boule de feu</option><option>Glace</option></select>
                            <select name="spells[]" class="bg-[#111] border border-gray-700 text-xs text-white rounded p-2"><option>Soin</option><option>Protection</option></select>
                            <select name="spells[]" class="bg-[#111] border border-gray-700 text-xs text-white rounded p-2"><option>Foudre</option><option>Silence</option></select>
                        </div>
                    </div>
                </div>

                <button type="submit" name="create_hero" class="w-full py-4 bg-gradient-to-r from-[#941515] to-red-900 hover:from-red-600 hover:to-red-800 text-white font-bold uppercase tracking-widest rounded shadow-lg transform hover:scale-[1.01] transition-all">
                    Incarner ce Héros
                </button>

            </form>
        </div>
    </div>
</main>

<script>
    // 1. Gestion de la Modal
    function toggleModal(modalID){
        const modal = document.getElementById(modalID);
        modal.classList.toggle("hidden");
        // Reset des champs si on ferme ? Optionnel.
    }

    // 2. Gestion des Lancers de dés (Roll)
    function rollDice(inputId, maxVal) {
        const input = document.getElementById(inputId);
        // Animation simple : on génère quelques nombres avant le final
        let counter = 0;
        const interval = setInterval(() => {
            input.value = Math.floor(Math.random() * maxVal) + 1;
            counter++;
            if(counter > 10) {
                clearInterval(interval);
                // Valeur finale
                const finalVal = Math.floor(Math.random() * maxVal) + 1;
                input.value = finalVal;
                // Effet visuel
                input.style.borderColor = "#f2a900";
                setTimeout(() => input.style.borderColor = "#374151", 500);
            }
        }, 50);
    }

    // 3. Affichage conditionnel selon la classe
    function updateClassFields() {
        const select = document.getElementById('classSelect');
        const shieldDiv = document.getElementById('shieldField');
        const spellDiv = document.getElementById('spellField');
        const shieldInput = document.getElementById('shieldInput');

        const value = select.value;

        // Reset
        shieldDiv.classList.add('hidden');
        spellDiv.classList.add('hidden');
        shieldInput.value = ""; // Clear shield value if hidden

        if (value === 'Warrior') {
            shieldDiv.classList.remove('hidden');
        } else if (value === 'Wizard') {
            spellDiv.classList.remove('hidden');
        }
        // Stealer n'a ni bouclier ni sorts (dans cet exemple)
    }

    // Lancer au chargement pour être sûr de l'état initial
    window.onload = updateClassFields;
</script>

<?php include_once("../php/components/footer.php"); ?>