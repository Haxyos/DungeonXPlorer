<?php
session_start();

// 1. Sécurité : Redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php?message=Veuillez vous connecter");
    exit();
}

include_once("../php/components/header.php");

// 2. Inclusion de la connexion BDD
// Comme ton fichier Database.php crée directement la variable $db, on l'inclut simplement.
// (On suppose que dashboard.php est dans /php/game/ et Database.php dans /php/)
require_once("../php/Database.php"); 

$userId = $_SESSION['user_id'];
$heroes = [];
$error = null;

// 3. Récupération des héros existants
try {
    // On utilise directement $db défini dans Database.php
    $stmt = $db->prepare("SELECT * FROM hero WHERE user_id = :user_id ORDER BY id DESC");
    $stmt->execute([':user_id' => $userId]);
    $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des héros : " . $e->getMessage();
}

// 4. Traitement du formulaire de création (si soumis)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_hero'])) {
    try {
        $name = htmlspecialchars($_POST['name']);
        $classId = intval($_POST['class_id']);
        $biography = htmlspecialchars($_POST['biography']);
        $image = htmlspecialchars($_POST['image']);
        
        // Stats de base (Niveau 1)
        // Note : Idéalement, ces valeurs devraient changer selon la classe choisie via un switch($classId)
        $pv = 100; 
        $mana = 50;
        $strength = 10;
        $initiative = 5;
        $xp = 0;
        $current_level = 1;
        
        // Insertion
        $sql = "INSERT INTO hero (name, class_id, image, biography, pv, mana, strength, initiative, xp, current_level, user_id) 
                VALUES (:name, :class_id, :image, :bio, :pv, :mana, :str, :init, :xp, :lvl, :uid)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':class_id' => $classId,
            ':image' => $image ?: 'https://via.placeholder.com/300x400/1a1a1a/f2a900?text=No+Image', // Image par défaut
            ':bio' => $biography,
            ':pv' => $pv,
            ':mana' => $mana,
            ':str' => $strength,
            ':init' => $initiative,
            ':xp' => $xp,
            ':lvl' => $current_level,
            ':uid' => $userId
        ]);

        // Rechargement pour éviter la resoumission du formulaire
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } catch (PDOException $e) {
        $error = "Erreur lors de la création : " . $e->getMessage();
    }
}
?>

<main class="min-h-screen bg-[#1A1A1A] font-sans text-white py-12 px-6 relative">
    
    <div class="max-w-7xl mx-auto mb-12 text-center border-b border-gray-800 pb-8 animate-fade-in-down">
        <h2 class="text-4xl md:text-5xl font-extrabold uppercase text-[#f2a900] mb-2 drop-shadow-lg">
            Vos Héros
        </h2>
        <p class="text-gray-400 italic">"Choisissez votre avatar ou forgez une nouvelle légende."</p>
        
        <?php if($error): ?>
            <div class="mt-4 p-2 bg-red-900/50 border border-red-500 text-red-200 rounded text-sm inline-block">
                <?= $error ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-20">

        <div onclick="toggleModal('modal-create')" class="cursor-pointer group relative h-96 border-2 border-dashed border-gray-700 rounded-xl flex flex-col items-center justify-center hover:border-[#f2a900] hover:bg-gray-900/50 transition-all duration-300">
            <div class="w-20 h-20 rounded-full bg-gray-800 group-hover:bg-[#f2a900] flex items-center justify-center transition-colors duration-300 mb-4 shadow-lg">
                <i class="fa-solid fa-plus text-3xl text-gray-400 group-hover:text-black"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-400 group-hover:text-white uppercase tracking-wider">Nouveau Personnage</h3>
        </div>

        <?php foreach ($heroes as $hero): ?>
        <div class="group relative bg-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-lg hover:shadow-[0_0_20px_rgba(242,169,0,0.3)] hover:-translate-y-2 transition-all duration-300">
            
            <div class="h-48 w-full bg-cover bg-center relative" style="background-image: url('<?= htmlspecialchars($hero['image']) ?>');">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                <div class="absolute top-2 right-2 bg-[#941515] text-white text-xs font-bold px-2 py-1 rounded border border-red-400 shadow-sm">
                    Niv. <?= $hero['current_level'] ?>
                </div>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-2xl font-bold text-[#f2a900]"><?= htmlspecialchars($hero['name']) ?></h3>
                </div>
                
                <p class="text-sm text-gray-400 mb-4 italic flex items-center">
                    <i class="fa-solid fa-chess-pawn mr-2 text-gray-600"></i> Classe ID: <?= $hero['class_id'] ?>
                </p>

                <div class="flex justify-between text-sm text-gray-300 mb-4 border-t border-gray-800 pt-4 px-2">
                    <div class="flex items-center gap-2" title="Points de Vie">
                        <i class="fa-solid fa-heart text-red-600"></i> 
                        <span class="font-mono font-bold"><?= $hero['pv'] ?></span>
                    </div>
                    <div class="flex items-center gap-2" title="Mana">
                        <i class="fa-solid fa-bolt text-blue-400"></i> 
                        <span class="font-mono font-bold"><?= $hero['mana'] ?></span>
                    </div>
                    <div class="flex items-center gap-2" title="Force">
                        <i class="fa-solid fa-hand-fist text-orange-500"></i> 
                        <span class="font-mono font-bold"><?= $hero['strength'] ?></span>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 line-clamp-2 mb-6 h-8 leading-relaxed">
                    <?= htmlspecialchars($hero['biography'] ?: "L'histoire de ce héros reste à écrire...") ?>
                </p>

                <div class="flex gap-2">
                    <a href="./play.php?hero_id=<?= $hero['id'] ?>" class="flex-1 py-2 text-center bg-[#f2a900] hover:bg-yellow-500 text-black font-bold rounded uppercase text-sm transition-colors shadow-md">
                        Jouer
                    </a>
                    <button class="px-3 py-2 text-red-500 border border-gray-700 rounded hover:bg-red-900/30 hover:border-red-500 transition-colors">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <div id="modal-create" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-create')"></div>
        
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-[#111] border-2 border-[#941515] rounded-lg shadow-[0_0_50px_rgba(148,21,21,0.5)] p-8">
            
            <div class="flex justify-between items-center mb-6 border-b border-gray-800 pb-4">
                <h3 class="text-2xl font-bold text-white"><span class="text-[#f2a900]">Nouveau</span> Aventurier</h3>
                <button onclick="toggleModal('modal-create')" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <form action="" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nom du Héros</label>
                    <input type="text" name="name" required class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:border-[#f2a900] focus:outline-none focus:ring-1 focus:ring-[#f2a900] transition placeholder-gray-600" placeholder="Ex: Grog le Barbare">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Classe</label>
                    <div class="relative">
                        <select name="class_id" class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:border-[#f2a900] focus:outline-none transition appearance-none cursor-pointer">
                            <option value="1">Guerrier (Tank)</option>
                            <option value="2">Mage (DPS Distance)</option>
                            <option value="3">Voleur (DPS Corps à corps)</option>
                            <option value="4">Clerc (Soigneur)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Avatar (URL de l'image)</label>
                    <input type="url" name="image" class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:border-[#f2a900] focus:outline-none transition placeholder-gray-600" placeholder="https://exemple.com/image.jpg">
                    <p class="text-[10px] text-gray-500 mt-1 italic">Laissez vide pour l'image par défaut.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Histoire</label>
                    <textarea name="biography" rows="3" class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:border-[#f2a900] focus:outline-none transition placeholder-gray-600" placeholder="Quel est le passé de votre héros ?"></textarea>
                </div>

                <button type="submit" name="create_hero" class="w-full py-4 mt-2 bg-gradient-to-r from-[#941515] to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold uppercase tracking-widest rounded shadow-lg transform hover:scale-[1.01] active:scale-95 transition-all">
                    Invoquer le personnage
                </button>
            </form>

        </div>
    </div>

</main>

<script>
    // Fonction simple pour afficher/masquer la modal
    function toggleModal(modalID){
        const modal = document.getElementById(modalID);
        modal.classList.toggle("hidden");
    }
</script>

<?php include_once("../php/components/footer.php"); ?>