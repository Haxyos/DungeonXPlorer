<?php
    include_once("../components/header.php");

    $message_error = $_GET['message_error'] ?? '';
    $message_success = $_GET['message_success'] ?? '';

    if(isset($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
?>


<div class="flex flex-col items-center justify-center h-screen pb-10">

    <div class="text-white text-center mb-10 max-w-xl">
        <h1 class="text-6xl md:text-7xl font-extrabold mb-3 text-red-500 tracking-wider drop-shadow-lg">
            DungeonXplorer
        </h1>
        <p class="pl-2 text-xl text-gray-400 font-light italic">
            Bienvenue dans les Terres Oubliées. Authentifiez-vous pour continuer votre quête.
        </p>
    </div>

    <div class="w-full max-w-sm bg-gray-900 p-8 rounded-xl shadow-2xl border border-red-900/50 transform transition-all hover:shadow-red-900/70">

        <?php if ($message_error): ?>
            <div class="mb-5 p-3 text-center bg-red-900/30 border border-red-700 text-red-300 rounded font-medium">
                <?= htmlspecialchars($message_error) ?>
            </div>
        <?php endif; ?>

        <?php if ($message_success): ?>
            <div class="mb-5 p-3 text-center bg-green-900/30 border border-green-700 text-green-300 rounded font-medium">
                <?= htmlspecialchars($message_success) ?>
            </div>
        <?php endif; ?>

        <form action="./loginAPI.php" method="GET" class="space-y-6">

            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-300">
                    Sceau de l'Aventurier (Email)
                </label>
                <input id="email" type="email" name="email" required
                       class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 transition duration-300 placeholder-gray-500">
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-300">
                    Mot de Passe Secret
                </label>
                <input id="password" type="password" name="password" required
                       class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 transition duration-300 placeholder-gray-500">
            </div>

            <button type="submit"
                    class="w-full py-3 mt-6 rounded-lg font-bold text-white bg-red-700 hover:bg-red-600 transition duration-300 ease-in-out shadow-lg shadow-red-900/50 hover:shadow-xl hover:shadow-red-800/70 transform hover:scale-[1.01] uppercase">
                Déverrouiller le Portail
            </button>
            
            <p class="text-center text-sm text-gray-500 mt-4">
                Pas encore d'aventurier ? 
                <a href="./register.php" class="text-red-500 hover:text-red-400 font-medium hover:underline transition duration-300">
                    Créer un nouveau Héros
                </a>
            </p>
        </form>
    </div>

</div>


<?php 
    include_once("../components/footer.php");
?>