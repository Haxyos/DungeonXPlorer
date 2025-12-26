<?php
include_once('./components/header.php');
?>

<main>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl mb-20">
            <div class="group relative bg-gray-900/60 backdrop-blur-md border border-gray-700 rounded-xl p-8 transition-all duration-300 ">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-12 h-12 bg-[#941515] rounded-full flex items-center justify-center border-4 border-[#1A1A1A]">
                    <i class="fa-solid fa-scroll text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#f2a900] mb-3">Nouvelle Aventure</h3>
                <p class="text-gray-400 text-sm mb-6 h-12">Créez votre héros et plongez dans l'univers défini par votre Maître du Jeu.</p>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="./php/game/create_character.php" class="inline-block w-full py-3 bg-[#f2a900]  text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Commencer
                    </a>
                <?php else: ?>
                    <a href="./php/connection/register.php" class="inline-block w-full py-3 bg-[#f2a900] text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                        S'inscrire pour jouer
                    </a>
                <?php endif; ?>
            </div>

            <div class="group relative bg-gray-900/60 backdrop-blur-md border border-gray-700 rounded-xl p-8 transition-all duration-300 ">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center border-4 border-[#1A1A1A]">
                    <i class="fa-solid fa-dice-d20 text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-red-500 mb-3">Continuer</h3>
                <p class="text-gray-400 text-sm mb-6 h-12">Retrouvez votre fiche de personnage et l'histoire là où vous l'avez laissée.</p>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="./php/game/dashboard.php" class="inline-block w-full py-3 border-2 border-red-600 text-red-500 font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Reprendre
                    </a>
                <?php else: ?>
                    <a href="./php/connection/login.php" class="inline-block w-full py-3 border-2 border-white text-white font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Se connecter
                    </a>
                <?php endif; ?>
            </div>
        </div>


</main>

<?php 
    include_once("./components/footer.php");
?>