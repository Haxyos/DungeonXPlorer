<?php
session_start();

// 1. SÉCURITÉ : Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// 2. CONNEXION BDD (Adapte le chemin selon ton arborescence)
require_once "../Database.php"; 

// 3. SÉCURITÉ : Vérifier si l'utilisateur est bien ADMIN
// On revérifie en base de données pour éviter la falsification de session
try {
    $stmt = $db->prepare("SELECT est_admin FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $currentUser = $stmt->fetch();

    if (!$currentUser || $currentUser['est_admin'] != 1) {
        // Si pas admin, on redirige vers l'accueil
        header("Location: ../index.php?message=Accès interdit");
        exit();
    }
} catch (PDOException $e) {
    die("Erreur de vérification des droits.");
}

$message = "";
$messageType = ""; // 'success' ou 'error'

// 4. TRAITEMENT DU FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $isAdmin = isset($_POST['est_admin']) ? 1 : 0;

    // Vérification des champs vides
    if (empty($username) || empty($email) || empty($password)) {
        $message = "Tous les champs sont obligatoires.";
        $messageType = "error";
    } else {
        try {
            // Vérifier si l'utilisateur ou l'email existe déjà
            $checkStmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $checkStmt->execute([$username, $email]);
            
            if ($checkStmt->rowCount() > 0) {
                $message = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
                $messageType = "error";
            } else {
                // Hachage du mot de passe (Indispensable pour la sécurité)
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insertion
                $sql = "INSERT INTO users (username, motDePasse, email, est_admin) VALUES (:user, :pass, :email, :admin)";
                $insertStmt = $db->prepare($sql);
                $insertStmt->execute([
                    ':user' => $username,
                    ':pass' => $hashedPassword,
                    ':email' => $email,
                    ':admin' => $isAdmin
                ]);

                $message = "Utilisateur <strong>" . htmlspecialchars($username) . "</strong> créé avec succès !";
                $messageType = "success";
            }
        } catch (PDOException $e) {
            $message = "Erreur technique : " . $e->getMessage();
            $messageType = "error";
        }
    }
}

// Inclusion du header (Adapte le chemin si besoin)
include_once("../components/header.php");
?>

<main class="min-h-screen flex flex-col items-center justify-center font-sans text-white bg-[#1A1A1A] py-20 px-4">

    <div class="max-w-md w-full text-center mb-8 animate-fade-in-down">
        <h2 class="text-3xl font-extrabold uppercase text-red-600 mb-2 drop-shadow-lg">
            <i class="fa-solid fa-user-shield mr-2"></i> Administration
        </h2>
        <p class="text-gray-400 italic">Ajouter un nouvel utilisateur à la base de données.</p>
    </div>

    <div class="w-full max-w-lg bg-[#111] border-2 border-[#941515] rounded-lg shadow-[0_0_50px_rgba(148,21,21,0.2)] p-8 relative">
        
        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 rounded border <?= $messageType === 'success' ? 'bg-green-900/50 border-green-500 text-green-200' : 'bg-red-900/50 border-red-500 text-red-200' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-6">
            
            <div>
                <label class="block text-[#f2a900] font-bold text-sm uppercase mb-2">
                    <i class="fa-solid fa-user mr-1"></i> Nom d'utilisateur
                </label>
                <input type="text" name="username" required 
                    class="w-full px-4 py-3 bg-[#1A1A1A] border border-gray-700 rounded text-white focus:outline-none focus:border-[#f2a900] focus:ring-1 focus:ring-[#f2a900] transition"
                    placeholder="Ex: DarkLord99">
            </div>

            <div>
                <label class="block text-[#f2a900] font-bold text-sm uppercase mb-2">
                    <i class="fa-solid fa-envelope mr-1"></i> Email
                </label>
                <input type="email" name="email" required 
                    class="w-full px-4 py-3 bg-[#1A1A1A] border border-gray-700 rounded text-white focus:outline-none focus:border-[#f2a900] focus:ring-1 focus:ring-[#f2a900] transition"
                    placeholder="Ex: contact@dungeon.fr">
            </div>

            <div>
                <label class="block text-[#f2a900] font-bold text-sm uppercase mb-2">
                    <i class="fa-solid fa-lock mr-1"></i> Mot de passe
                </label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-3 bg-[#1A1A1A] border border-gray-700 rounded text-white focus:outline-none focus:border-[#f2a900] focus:ring-1 focus:ring-[#f2a900] transition"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center p-4 bg-[#1A1A1A] border border-gray-700 rounded">
                <input type="checkbox" id="est_admin" name="est_admin" value="1" 
                    class="w-5 h-5 text-red-600 bg-gray-900 border-gray-600 rounded focus:ring-red-500 focus:ring-2">
                <label for="est_admin" class="ml-3 text-sm font-medium text-gray-300">
                    Accorder les droits <span class="text-red-500 font-bold">ADMINISTRATEUR</span>
                </label>
            </div>

            <button type="submit" class="w-full py-4 mt-2 bg-gradient-to-r from-[#941515] to-red-900 hover:from-red-700 hover:to-red-800 text-white font-bold uppercase tracking-widest rounded shadow-lg transform hover:scale-[1.01] transition-all">
                <i class="fa-solid fa-plus-circle mr-2"></i> Créer l'utilisateur
            </button>

        </form>

        <div class="mt-6 text-center border-t border-gray-800 pt-4">
            <a href="./index.php" class="text-gray-500 hover:text-[#f2a900] text-sm transition">
                <i class="fa-solid fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>

    </div>
</main>

<?php include_once("../components/footer.php"); ?>