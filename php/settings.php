<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}
include("./Database.php");
include_once('./components/header.php');

$sql = "SELECT email, username, motDePasse FROM users WHERE id = :id";
$stmt = $db->prepare($sql);
$stm = $stmt->execute(['id' => $_SESSION['user_id']]);
$result = $stmt->fetch();

$email = $result['email'];
$mdp = $result['motDePasse'];
$userName =  $result['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = htmlspecialchars(trim($_POST['newUser'] ??  ""), ENT_QUOTES, 'UTF-8');
    $newEmail = trim($_POST['newEmail'] ?? "");
    $newPassword = $_POST['newPassword'] ??  "";

    
    if($newPassword == ""){
        $newPassword = $mdp;
    }
    if($newEmail == ""){
        $newEmail = $email;
    }
    if($newUsername == ""){
        $newUsername = $userName;
    }
    if (strlen($newPassword) < 8) {
        redirect_on_error("La Clé de la Crypte doit contenir au moins 8 caractères.");
    }

    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);


    $sql = "UPDATE users set motDePasse = :newPassword, email = :newEmail, userName = :newUsername
                WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->execute([
        'id' => $_SESSION['user_id'],
        'newEmail' => $newEmail,
        'newPassword' => $newPassword,
        'newUsername' => $newUsername
    ]);
}

?>

<main class="min-h-screen flex flex-col items-center justify-center font-sans text-white bg-[#1A1A1A]">
    <div class="absolute grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl mb-20">
        <div class="group relative bg-gray-900/60 backdrop-blur-md border border-gray-700 rounded-xl p-8 transition-all duration-300 ">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-12 h-12 bg-[#941515] rounded-full flex items-center justify-center border-4 border-[#1A1A1A]">
                <i class="fa-solid fa-address-card"></i>
            </div>
            <h3 class="text-2xl font-bold text-[#f2a900] mb-3">Settings</h3>
            <p class="text-gray-400 text-sm mb-2 h-6">Modifier vos paramètre de compte</p>

            <div class="my-1 py-1">
                <div class="relative my-4">
                    Adresse mail : <?php echo $email ?>
                </div>
                <div class="relative my-4">
                    Nom d'utilisateur : <?php echo $userName ?>
                </div>
            </div>
            <button id="openModal" class="py-3 px-6 bg-[#f2a900] text-black font-bold rounded uppercase tracking-wide transition shadow-lg hover:bg-[#d49500]">
                Ouvrir les paramètres
            </button>

            <!-- Modal Overlay -->
            <div id="modalOverlay" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 hidden transition-opacity duration-300"></div>

            <!-- Modal -->
            <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
                <div class="relative bg-gray-900/90 backdrop-blur-md border border-gray-700 rounded-xl p-8 w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="modalContent">

                    <!-- Icône en haut à droite -->
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-12 h-12 bg-[#941515] rounded-full flex items-center justify-center border-4 border-[#1A1A1A]">
                        <i class="fa-solid fa-address-card"></i>
                    </div>

                    <!-- Bouton de fermeture -->
                    <button id="closeModal" class="absolute top-11 right-6 text-gray-400 hover:text-white transition">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>

                    <!-- Titre -->
                    <h3 class="text-2xl font-bold text-[#f2a900] mb-3">Modifier</h3>
                    <p class="text-gray-400 text-sm mb-6">Modifier vos paramètres de compte</p>

                    <!-- Contenu du modal -->
                    <form action="settings.php" method="POST" class="space-y-6">

                        <!-- Email -->
                        <div class="relative py-3 border-b border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-gray-400 text-sm">Adresse mail :</span>
                                    <input type="text" class="text-black" name="newEmail" id="newEmail">
                                </div>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="relative py-3 border-b border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-gray-400 text-sm">Nouveau mot de passe :</span>
                                    <input type="text" class="text-black" name="newPassword" id="newPassword">
                                </div>
                            </div>
                        </div>
                        <!-- Nom d'utilisateur -->
                        <div class="relative py-3 border-b border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-gray-400 text-sm">nouveau nom d'utilisateur :</span>
                                    <input type="text" class="text-black" name="newUser" id="newUser">
                                </div>

                            </div>
                        </div>
                        <!-- Bouton de fermeture en bas -->
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="py-1 px-3 bg-[#f2a900] text-black font-bold text-sm rounded uppercase tracking-wide transition shadow-lg hover:bg-[#d49500]">
                                Valider
                            </button>
                        </div>
                    </form>



                </div>
            </div>

            <script>
                const openModalBtn = document.getElementById('openModal');
                const closeModalBtn = document.getElementById('closeModal');
                const closeModalBtnBottom = document.getElementById('closeModalBtn');
                const modal = document.getElementById('modal');
                const modalOverlay = document.getElementById('modalOverlay');
                const modalContent = document.getElementById('modalContent');

                function openModal() {
                    modal.classList.remove('hidden');
                    modalOverlay.classList.remove('hidden');
                    setTimeout(() => {
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }

                function closeModal() {
                    modalContent.classList.remove('scale-100', 'opacity-100');
                    modalContent.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modalOverlay.classList.add('hidden');
                    }, 300);
                }

                openModalBtn.addEventListener('click', openModal);
                closeModalBtn.addEventListener('click', closeModal);
                closeModalBtnBottom.addEventListener('click', closeModal);
                modalOverlay.addEventListener('click', closeModal);

                // Fermer avec la touche Echap
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            </script>

</main>

<?php
include_once("./components/footer.php");
?>