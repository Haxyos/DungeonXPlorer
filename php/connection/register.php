<?php
include_once("../components/header.php");

$message = $_GET['message'] ?? '';

if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}
?>


<div class="flex flex-col items-center justify-center min-h-screen pt-20 pb-10">

    <div class="text-white text-center mb-10 max-w-xl">
        <h1 class="text-6xl md:text-7xl font-extrabold mb-3 text-red-500 tracking-wider drop-shadow-lg">
            Création de Héros
        </h1>
        <p class="text-xl text-gray-400 font-light italic">
            Répondez à l'appel de l'aventure et forgez votre légende !
        </p>
    </div>

    <div class="w-full max-w-sm bg-gray-900 p-8 rounded-xl shadow-2xl border border-red-900/50 transform transition-all hover:shadow-red-900/70">

        <?php if ($message): ?>
            <div class="mb-5 p-3 text-center bg-red-900/30 border border-red-700 text-red-300 rounded font-medium">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="./registerAPI.php" method="POST" class="space-y-6">

            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-gray-300">
                    Nom du Héros
                </label>
                <input id="username" type="text" name="username" required
                    class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 transition duration-300 placeholder-gray-500"
                    placeholder="Ex: L'Épée Écarlate">
            </div>

            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-300">
                    Sceau du Domaine (Email)
                </label>
                <input id="email" type="email" name="email" required
                    class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 transition duration-300 placeholder-gray-500"
                    placeholder="Ex: votre@guilde.com">
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-300">
                    Clé de la Crypte (Mot de Passe)
                </label>
                <input id="password" type="password" name="password" required
                    class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 transition duration-300 placeholder-gray-500">
            </div>

            <div>
                <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-300">
                    Répéter la Clé de la Crypte
                </label>
                <input id="confirm_password" type="password" name="confirm_password" required
                    class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 transition duration-300 placeholder-gray-500">
            </div>

            <button type="submit"
                class="w-full py-3 mt-6 rounded-lg font-bold text-white bg-green-700 hover:bg-green-600 transition duration-300 ease-in-out shadow-lg shadow-green-900/50 hover:shadow-xl hover:shadow-green-800/70 transform hover:scale-[1.01] uppercase">
                Créer mon héros
            </button>

            <p class="text-center text-sm text-gray-500 mt-4">
                Déjà enrôlé ?
                <a href="./login.php" class="text-red-500 hover:text-red-400 font-medium hover:underline transition duration-300">
                    Retour au Portail de Connexion
                </a>
            </p>
        </form>
    </div>

</div>


<?php
include_once("../components/footer.php");
?>