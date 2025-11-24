<?php
    include_once("../components/header.php");

    $message = $_GET['message'] ?? '';


    if(isset($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
?>

<main class="flex-grow flex flex-col items-center justify-center px-6 py-16">

        <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
            
            <div class="text-center mb-6">
                <img src="/images/Logo.png" class="h-12 mx-auto mb-3" alt="Logo DungeonXPlorer">
                <h2 class="text-2xl font-bold text-[#00205b]">Connexion</h2>
                <p class="text-gray-500 text-sm">Accédez à votre espace personnel</p>
            </div>

            <?php if ($message): ?>
                <div class="mb-4 text-center text-red-600 font-medium">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="../api_php/loginAPI.php" method="GET" class="space-y-5">

                <div>
                    <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Adresse e-mail</label>
                    <input id="email" type="email" name="email" required
                           class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#005bb5]">
                </div>

                <div>
                    <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Mot de passe</label>
                    <input id="password" type="password" name="password" required
                           class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#005bb5]">
                </div>

                <button type="submit"
                        class="w-full bg-[#005bb5] hover:bg-[#003f80] text-white font-semibold py-2.5 rounded-lg shadow transition">
                    Connexion
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Pas encore de compte ?
                <a href="createAccount.php" class="text-[#005bb5] font-medium hover:underline">
                    Créer un compte
                </a>
            </p>
        </div>
    </main>


<?php 
    include_once("../components/footer.php");
?>