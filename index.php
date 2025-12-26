<?php
    session_start();
    include_once("./php/components/header.php");
    $message = $_GET['message'] ?? '';
?>

<main class="min-h-screen flex flex-col items-center justify-center font-sans text-white bg-[#1A1A1A]">

    <div class="z-10 mx-auto px-6 py-24 flex flex-col items-center text-center">

        <div class="mb-16 animate-fade-in-down">
            <h1 class="text-5xl md:text-7xl font-extrabold uppercase mb-4 shadow-md">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#f2a900] to-yellow-600">
                    Dungeon
                </span>
                <span class="text-white">Xplorer</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 font-light max-w-2xl mx-auto italic">
                "Le destin ne distribue pas les cartes, il ne fait que les mélanger. À vous de jouer."
            </p>
            
            <?php if ($message): ?>
                <div class="mt-6 p-3 bg-red-900/50 border border-red-500 text-red-200 rounded backdrop-blur-sm">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl mb-20">
            <div class="group relative bg-gray-900/60 backdrop-blur-md border border-gray-700 hover:border-[#f2a900] rounded-xl p-8 transition-all duration-300 hover:transform hover:-translate-y-2 hover:shadow-[0_0_30px_rgba(242,169,0,0.2)]">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-12 h-12 bg-[#941515] rounded-full flex items-center justify-center border-4 border-[#1A1A1A]">
                    <i class="fa-solid fa-scroll text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#f2a900] mb-3">Nouvelle Aventure</h3>
                <p class="text-gray-400 text-sm mb-6 h-12">Créez votre héros et plongez dans l'univers défini par votre Maître du Jeu.</p>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/caracter/selectionCharacter.php" class="inline-block w-full py-3 bg-[#f2a900] hover:bg-yellow-500 text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Commencer
                    </a>
                <?php else: ?>
                    <a href="./php/connection/register.php" class="inline-block w-full py-3 bg-[#f2a900] hover:bg-yellow-500 text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                        S'inscrire pour jouer
                    </a>
                <?php endif; ?>
            </div>

            <div class="group relative bg-gray-900/60 backdrop-blur-md border border-gray-700 hover:border-red-500 rounded-xl p-8 transition-all duration-300 hover:transform hover:-translate-y-2 hover:shadow-[0_0_30px_rgba(148,21,21,0.3)]">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center border-4 border-[#1A1A1A]">
                    <i class="fa-solid fa-dice-d20 text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-red-500 mb-3">Continuer</h3>
                <p class="text-gray-400 text-sm mb-6 h-12">Retrouvez votre fiche de personnage et l'histoire là où vous l'avez laissée.</p>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/caracter/selectionCharacter.php" class="inline-block w-full py-3 border-2 border-red-600 text-red-500 hover:bg-red-600 hover:text-white font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Reprendre
                    </a>
                <?php else: ?>
                    <a href="./php/connection/login.php" class="inline-block w-full py-3 border-2 border-white text-white hover:bg-white hover:text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                        Se connecter
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="w-full max-w-5xl bg-[#1A1A1A]/90 border-t-4 border-[#941515] p-8 md:p-12 shadow-2xl rounded-b-lg text-left">
            <div class="flex flex-col md:flex-row gap-10 items-start">
                
                <div class="md:w-2/3">
                    <h2 class="text-3xl font-bold text-white mb-6 border-l-4 border-[#f2a900] pl-4">
                        L'Édito du Gardien
                    </h2>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Bienvenue sur <strong>DungeonXplorer</strong>. Ce projet est né d'une passion commune pour les mondes imaginaires et les lancers de dés critiques.
                    </p>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Ici, vous ne trouverez pas un créateur de mondes infinis, mais une <strong>porte d'entrée unique</strong> vers une aventure soigneusement préparée. Ce site a été conçu pour simplifier la vie des joueurs : plus de feuilles perdues, plus de calculs fastidieux.
                    </p>
                    <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 mt-6">
                        <h4 class="text-[#f2a900] font-bold mb-2"><i class="fa-solid fa-book-open mr-2"></i>Qu'est-ce qu'un Jeu de Rôle ?</h4>
                        <p class="text-sm text-gray-400 italic">
                            "Un jeu de rôle (JDR) est une expérience narrative où vous incarnez un personnage fictif. À travers vos choix et la chance aux dés, vous influencez le déroulement d'une histoire contée par le Maître du Jeu."
                        </p>
                    </div>
                </div>

                <div class="md:w-1/3 w-full bg-[#111] p-6 rounded border border-gray-800">
                    <h3 class="text-xl font-bold text-gray-200 mb-4 border-b border-gray-700 pb-2">
                        Dernières Actualités
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <span class="text-[#941515] mr-2 mt-1"><i class="fa-solid fa-dragon"></i></span>
                            <div>
                                <span class="block text-[#f2a900] text-sm font-bold">Mise à jour 1.2</span>
                                <span class="text-gray-500 text-xs">Ajout des fiches de bestiaire.</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-[#941515] mr-2 mt-1"><i class="fa-solid fa-skull"></i></span>
                            <div>
                                <span class="block text-[#f2a900] text-sm font-bold">Campagne "Val Perdu"</span>
                                <span class="text-gray-500 text-xs">Le chapitre 2 est disponible !</span>
                            </div>
                        </li>
                    </ul>
                    
                    <div class="mt-8 text-center">
                        <p class="text-xs text-gray-500 mb-2">Prêt à rejoindre la table ?</p>
                        <i class="fa-solid fa-arrow-up text-gray-600 animate-bounce"></i>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

<?php 
    include_once("./php/components/footer.php");
?>