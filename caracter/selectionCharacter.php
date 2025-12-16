<?php
session_start();

// 1. Sécurité
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?message=Veuillez vous connecter");
    exit();
}

// 2. Inclusions Techniques (Base de données + Classes)
// IMPORTANT : On n'inclut PAS encore le header.php ici car il contient du HTML !
require_once("../php/Database.php");
require_once '../Classe/Warrior.php';
require_once '../Classe/Stealer.php';
require_once '../Classe/Wizard.php';

$userId = $_SESSION['user_id'];
$heroes = [];
$error = null;

// 3. Récupération des sorts (Nécessaire pour le formulaire sorcier)
$spells = [];
try {
    $spellQuery = $db->query("SELECT id, nom, cout_mana FROM Spell ORDER BY cout_mana ASC");
    $spells = $spellQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // On ignore si la table n'existe pas encore
}

// ===============================================
// 4. LOGIQUE DE SUPPRESSION (AVANT LE HTML)
// ===============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_hero_id'])) {
    $heroIdToDelete = intval($_POST['delete_hero_id']);

    if ($heroIdToDelete > 0) {
        try {
            // Supprimer d'abord les dépendances pour éviter les erreurs de clés étrangères
            $db->prepare("DELETE FROM Hero_Progress WHERE hero_id = :id")->execute([':id' => $heroIdToDelete]);
            $db->prepare("DELETE FROM Pouvoir WHERE id_heros = :id")->execute([':id' => $heroIdToDelete]);
            
            // Supprimer le héros
            $stmt = $db->prepare("DELETE FROM Hero WHERE id = :id AND user_id = :user_id");
            $stmt->execute([':id' => $heroIdToDelete, ':user_id' => $userId]);

            // Redirection propre
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            $error = "Erreur de suppression : " . $e->getMessage();
        }
    }
}

// ===============================================
// 5. LOGIQUE DE CRÉATION (AVANT LE HTML)
// ===============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_hero'])) {

    $nom = htmlspecialchars($_POST["characterName"]);
    $classType = $_POST["class"]; 
    $descriptif = htmlspecialchars($_POST["descChar"]);
    $initiative = intval($_POST["initiative"]);
    
    // Valeurs par défaut
    $armeId = 0; $armureId = 0; $bouclierId = null;
    $img = 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=Hero';

    // Validation spécifique Sorcier
    if ($classType === 'Wizard') {
        if (empty($_POST['spell1']) || empty($_POST['spell2']) || empty($_POST['spell3'])) {
            $error = "Le sorcier doit choisir 3 sorts !";
        } elseif ($_POST['spell1'] == $_POST['spell2'] || $_POST['spell1'] == $_POST['spell3'] || $_POST['spell2'] == $_POST['spell3']) {
            $error = "Vous devez choisir 3 sorts différents !";
        }
    }

    if (!$error) {
        // Configuration selon la classe
        switch ($classType) {
            case "Warrior":
                $img = '/images/guerrier.png';
                $armeId = 10; $armureId = 40; $bouclierId = 30;
                break;
            case "Wizard":
                $img = '/images/sorcier.png';
                $armeId = 14; $armureId = 43; $bouclierId = null;
                break;
            case "Stealer":
                $img = '/images/voleur.png';
                $armeId = 13; $armureId = 44; $bouclierId = null;
                break;
        }

        try {
            $db->beginTransaction();

            $heroObj = null;
            $classId = 0;

            switch ($classType) {
                case "Warrior": $classId = 1; $heroObj = new Warrior(); break;
                case "Wizard": $classId = 2; $heroObj = new Wizard(); break;
                case "Stealer": $classId = 3; $heroObj = new Stealer(); break;
            }

            if ($heroObj) {
                if (method_exists($heroObj, 'constructeurAvecParam')) {
                    $heroObj->constructeurAvecParam($nom, $classId, $img, $descriptif, $initiative, $armureId, $armeId, 100);
                }

                $sql = 'INSERT INTO Hero (name, class_id, image, biography, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) 
                        VALUES (:name, :class, :img, :bio, :pv, :mana, :str, :init, :armor, :weapon, :shield, :xp, :lvl, :uid)';
                
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':name' => $heroObj->getName(), ':class' => $classId, ':img' => $img, ':bio' => $heroObj->getBiography(),
                    ':pv' => $heroObj->getHealth(), ':mana' => $heroObj->getMana(), ':str' => $heroObj->getStrength(),
                    ':init' => $heroObj->getInitiative(), ':armor' => $armureId, ':weapon' => $armeId,
                    ':shield' => $bouclierId, ':xp' => 0, ':lvl' => 1, ':uid' => $userId
                ]);
                
                $heroId = $db->lastInsertId();

                // Initialisation Progression
                $stmtProgress = $db->prepare("INSERT INTO Hero_Progress (hero_id, chapter_id, status, completion_date) VALUES (:hid, 1, 'In Progress', NOW())");
                $stmtProgress->execute([':hid' => $heroId]);

                // Insertion Sorts (Si Sorcier)
                if ($classType === 'Wizard') {
                    $spellStmt = $db->prepare('INSERT INTO Pouvoir (id_heros, id_spell) VALUES (:hero_id, :spell_id)');
                    $spellStmt->execute([':hero_id' => $heroId, ':spell_id' => $_POST['spell1']]);
                    $spellStmt->execute([':hero_id' => $heroId, ':spell_id' => $_POST['spell2']]);
                    $spellStmt->execute([':hero_id' => $heroId, ':spell_id' => $_POST['spell3']]);
                }

                $db->commit();
                
                // Redirection propre après création
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Erreur technique : " . $e->getMessage();
        }
    }
}

// 6. Récupération des données pour l'affichage (AVANT LE HTML)
try {
    $stmt = $db->prepare("SELECT * FROM Hero WHERE user_id = :user_id ORDER BY id DESC");
    $stmt->execute([':user_id' => $userId]);
    $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lecture héros : " . $e->getMessage();
}

// ===============================================
// 7. DÉBUT DE L'AFFICHAGE HTML (ICI ON PEUT INCLURE LE HEADER)
// ===============================================
include_once("../php/components/header.php"); 
?>

<main class="min-h-screen flex flex-col items-center justify-center font-sans text-white bg-[#1A1A1A]">

    <div class="max-w-7xl mx-auto mb-12 text-center border-b border-gray-800 pb-8 animate-fade-in-down mt-24">
        <h2 class="text-4xl md:text-5xl font-extrabold uppercase text-[#f2a900] mb-2 drop-shadow-lg">
            Guilde des Aventuriers
        </h2>
        <p class="text-gray-400 italic">Gérez vos héros et préparez votre prochaine quête.</p>

        <?php if ($error): ?>
            <div class="mt-4 p-3 bg-red-900/80 border border-red-500 text-white rounded font-bold inline-block">
                <?= $error ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-24 px-4">

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
                    
                    <form method="POST" action="" onsubmit="return confirmDelete(event, '<?= htmlspecialchars($hero['name']) ?>');" class="absolute top-2 right-2 z-20"> 
                        <input type="hidden" name="delete_hero_id" value="<?= $hero['id'] ?>">
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition duration-200 p-2 bg-gray-900/50 hover:bg-gray-800 rounded-full" title="Supprimer">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                    
                    <div class="absolute top-2 right-12 bg-[#941515] text-white text-xs font-bold px-2 py-1 rounded border border-red-400 z-10">
                        Niv. <?= $hero['current_level'] ?>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-2xl font-bold text-[#f2a900] mb-1"><?= htmlspecialchars($hero['name']) ?></h3>
                    <p class="text-xs text-gray-500 mb-4 uppercase tracking-widest flex items-center gap-2">
                        <?php
                        $icon = "fa-question"; $className = "Inconnu";
                        if ($hero['class_id'] == 1) { $className = "Guerrier"; $icon = "fa-shield-halved"; }
                        elseif ($hero['class_id'] == 2) { $className = "Sorcier"; $icon = "fa-hat-wizard"; }
                        elseif ($hero['class_id'] == 3) { $className = "Voleur"; $icon = "fa-mask"; }
                        ?>
                        <i class="fa-solid <?= $icon ?>"></i> <?= $className ?>
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

        <div class="relative min-h-screen md:min-h-0 md:absolute md:top-5 md:left-1/2 md:transform md:-translate-x-1/2 w-full max-w-4xl bg-[#1a1a1a] border-2 border-[#941515] rounded-none md:rounded-lg shadow-[0_0_50px_rgba(148,21,21,0.5)] p-6 md:p-8">

            <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                <h3 class="text-2xl font-bold text-white"><span class="text-[#f2a900]">Nouvelle</span> Aventure</h3>
                <button onclick="toggleModal('modal-create')" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <form action="" method="POST" class="space-y-6">
                <input type="hidden" name="create_hero" value="1">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[#f2a900] font-semibold mb-2 text-sm uppercase">Nom du personnage</label>
                            <input type="text" name="characterName" required placeholder="Ex: Grog le Barbare"
                                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded text-white placeholder-gray-500 focus:outline-none focus:border-[#f2a900] focus:ring-1 focus:ring-[#f2a900] transition">
                        </div>

                        <div>
                            <label class="block text-[#f2a900] font-semibold mb-2 text-sm uppercase">Classe</label>
                            <select id="classSelect" name="class" required
                                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded text-white focus:outline-none focus:border-[#f2a900] transition cursor-pointer">
                                <option value="Warrior">🛡️ Guerrier - Tank (15 PV, 0 Mana)</option>
                                <option value="Wizard">🔮 Sorcier - Arcanes (8 PV, 20 Mana)</option>
                                <option value="Stealer">🗡️ Voleur - Agile (10 PV, 5 Mana)</option>
                            </select>
                        </div>

                        <div class="flex justify-center py-4 bg-gray-900/50 rounded border border-gray-700">
                            <img id="classImagePreview" src="/images/guerrier.png" alt="Aperçu classe" class="h-32 w-auto pixel-art rendering-pixelated">
                        </div>

                        <div>
                            <label class="block text-[#f2a900] font-semibold mb-2 text-sm uppercase">Histoire</label>
                            <textarea name="descChar" rows="3" required placeholder="Son passé..."
                                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded text-white placeholder-gray-500 focus:outline-none focus:border-[#f2a900] transition resize-none"></textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        
                        <div id="equipmentInfo" class="bg-gray-900 border border-gray-600 rounded-lg p-4">
                            <h3 class="text-[#f2a900] font-semibold mb-3 text-sm uppercase">🎒 Équipement de départ</h3>
                            <div id="equipmentDetails" class="text-gray-300 text-sm space-y-2">
                                </div>
                        </div>

                        <div id="spellsDiv" style="display: none;" class="bg-gray-900 border border-gray-600 rounded-lg p-4">
                            <h3 class="text-[#f2a900] font-semibold mb-3 text-sm uppercase">✨ Grimoire (3 sorts)</h3>
                            <div class="space-y-2">
                                <select name="spell1" class="w-full px-3 py-2 bg-[#1a1a1a] border border-gray-600 rounded text-white text-sm">
                                    <option value="">Sort 1</option>
                                    <?php foreach ($spells as $s): ?><option value="<?= $s['id'] ?>"><?= $s['nom'] ?> (<?= $s['cout_mana'] ?> mana)</option><?php endforeach; ?>
                                </select>
                                <select name="spell2" class="w-full px-3 py-2 bg-[#1a1a1a] border border-gray-600 rounded text-white text-sm">
                                    <option value="">Sort 2</option>
                                    <?php foreach ($spells as $s): ?><option value="<?= $s['id'] ?>"><?= $s['nom'] ?> (<?= $s['cout_mana'] ?> mana)</option><?php endforeach; ?>
                                </select>
                                <select name="spell3" class="w-full px-3 py-2 bg-[#1a1a1a] border border-gray-600 rounded text-white text-sm">
                                    <option value="">Sort 3</option>
                                    <?php foreach ($spells as $s): ?><option value="<?= $s['id'] ?>"><?= $s['nom'] ?> (<?= $s['cout_mana'] ?> mana)</option><?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[#f2a900] font-semibold mb-2 text-sm uppercase">⚡ Initiative</label>
                            <input type="number" name="initiative" id="initiativeInput" value="0" min="0" max="10" required
                                class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded text-white focus:outline-none focus:border-[#f2a900] transition">
                            <p class="text-gray-500 text-xs mt-1" id="initiativeHelp">Entre 0 et 10</p>
                        </div>

                    </div>
                </div>

                <div class="pt-4 border-t border-gray-700">
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#941515] to-red-900 hover:from-red-600 hover:to-red-800 text-white font-bold uppercase tracking-widest rounded shadow-lg transform hover:scale-[1.01] transition-all">
                        Incarner ce Héros
                    </button>
                </div>

            </form>
        </div>
    </div>

</main>

<script>
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }

    function confirmDelete(event, heroName) {
        event.preventDefault();
        if (confirm(`Êtes-vous sûr de vouloir supprimer ${heroName} ?`)) {
            event.target.submit();
        }
    }

    const classSelect = document.getElementById('classSelect');
    const equipmentDetails = document.getElementById('equipmentDetails');
    const spellsDiv = document.getElementById('spellsDiv');
    const initiativeInput = document.getElementById('initiativeInput');
    const initiativeHelp = document.getElementById('initiativeHelp');
    const imagePreview = document.getElementById('classImagePreview');

    const equipmentByClass = {
        'Warrior': {
            weapon: '⚔️ Épée courte (+2 Force)',
            armor: '🛡️ Armure de cuir (+3 PV)',
            shield: '🛡️ Bouclier en bois (+2 PV)',
            img: '/images/guerrier.png'
        },
        'Wizard': {
            weapon: '🪄 Bâton du sage (+1 Force, +5 Mana)',
            armor: '👘 Robe de mage (+2 PV, +3 Mana)',
            shield: null,
            img: '/images/sorcier.png'
        },
        'Stealer': {
            weapon: '🗡️ Dague empoisonnée (+3 Force)',
            armor: '🥷 Tunique de voleur (+2 PV, +2 Init)',
            shield: null,
            img: '/images/voleur.png'
        }
    };

    function updateFormUI() {
        const selectedClass = classSelect.value;
        const data = equipmentByClass[selectedClass];
        
        imagePreview.src = data.img;

        let html = `<p class="flex items-center"><span class="mr-2 text-green-500">✔</span> ${data.weapon}</p>`;
        html += `<p class="flex items-center"><span class="mr-2 text-green-500">✔</span> ${data.armor}</p>`;
        if (data.shield) {
            html += `<p class="flex items-center"><span class="mr-2 text-green-500">✔</span> ${data.shield}</p>`;
        }
        equipmentDetails.innerHTML = html;

        const spellSelects = spellsDiv.querySelectorAll('select');
        if (selectedClass === 'Wizard') {
            spellsDiv.style.display = 'block';
            spellSelects.forEach(s => s.required = true);
        } else {
            spellsDiv.style.display = 'none';
            spellSelects.forEach(s => s.required = false);
        }

        if (selectedClass === 'Stealer') {
            initiativeInput.min = 5;
            initiativeInput.max = 15;
            initiativeInput.value = 8;
            initiativeHelp.textContent = 'Bonus Voleur : Entre 5 et 15';
        } else {
            initiativeInput.min = 0;
            initiativeInput.max = 10;
            initiativeInput.value = 0;
            initiativeHelp.textContent = 'Entre 0 et 10';
        }
    }

    classSelect.addEventListener('change', updateFormUI);
    window.onload = updateFormUI;
</script>

<style>
    .rendering-pixelated {
        image-rendering: pixelated;
        image-rendering: -moz-crisp-edges;
        image-rendering: crisp-edges;
    }
</style>

<?php include_once("../php/components/footer.php"); ?>