<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
?>

<main class="min-h-screen flex flex-col items-center justify-center font-sans text-white bg-[#1A1A1A]">
    <div class="abdolute grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl mb-20">
        <div class="group relative bg-gray-900/60 backdrop-blur-md border border-gray-700 rounded-xl p-8 transition-all duration-300 ">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-12 h-12 bg-[#941515] rounded-full flex items-center justify-center border-4 border-[#1A1A1A]">
                <i class="fa-solid fa-address-card"></i>
            </div>
            <h3 class="text-2xl font-bold text-[#f2a900] mb-3">Settings</h3>
            <p class="text-gray-400 text-sm mb-6 h-12">Modifier vos paramètre de compte</p>

            <div>
                <div>
                    Adresse mail : <?php echo $email ?>
                    <a href="#" class="inline-block w-full py-3 bg-[#f2a900]  text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Modifier l'email
                    </a>
                </div>
                <div>
                    Mot de passe :
                    <a href="#" class="inline-block w-full py-3 bg-[#f2a900]  text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Modifier mot de passe
                    </a>
                </div>
                <div>
                    Nom d'utilisateur : <?php echo $userName ?>
                    <a href="#" class="inline-block w-full py-3 bg-[#f2a900]  text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Modifier le nom d'utilisateur
                    </a>
                </div>
            </div>
        </div>
    </div>


</main>

<?php
include_once("./components/footer.php");
?>