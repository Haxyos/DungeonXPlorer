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

        <div class="<?php if (!isset($_SESSION['user_id'])) {echo 'grid grid-cols-1 md:grid-cols-2';} ?> gap-6 w-full max-w-3xl mb-20">
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

            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="group relative bg-gray-900/60 backdrop-blur-md border border-gray-700 hover:border-red-500 rounded-xl p-8 transition-all duration-300 hover:transform hover:-translate-y-2 hover:shadow-[0_0_30px_rgba(148,21,21,0.3)]">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center border-4 border-[#1A1A1A]">
                    <i class="fa-solid fa-dice-d20 text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-red-500 mb-3">Continuer</h3>
                <p class="text-gray-400 text-sm mb-6 h-12">Retrouvez votre fiche de personnage et l'histoire là où vous l'avez laissée.</p>

                <a href="./php/connection/login.php" class="inline-block w-full py-3 border-2 border-white text-white hover:bg-white hover:text-black font-bold rounded uppercase tracking-wide transition shadow-lg">
                    Se connecter
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="w-full max-w-5xl bg-[#1A1A1A]/90 border-t-4 border-[#941515] p-8 md:p-12 shadow-2xl rounded-b-lg text-left">
            <div class="flex flex-col md:flex-row">
                
                <div>
                    <h2 class="text-3xl font-bold text-white mb-6 border-l-4 border-[#f2a900] pl-4">
                        L'Édito du Gardien
                    </h2>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Bienvenue sur <strong>DungeonXplorer</strong>, l'univers de dark fantasy où se mêlent aventure, stratégie et immersion
                        totale dans les récits interactifs.
                    </p>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Ce projet est né de la volonté de l’association Les Aventuriers du Val Perdu de raviver l’expérience unique
                        des livres dont vous êtes le héros. Notre vision : offrir à la communauté un espace où chacun peut
                        incarner un personnage et plonger dans des quêtes épiques et personnalisées.
                    </p>
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Dans sa première version, DungeonXplorer permettra aux joueurs de créer un personnage parmi trois
                        classes emblématiques — guerrier, voleur, magicien — et d’évoluer dans un scénario captivant, tout en
                        assurant à chacun la possibilité de conserver sa progression.
                    </p>
                    <p class="text-gray-300 leading-relaxed">
                        Nous sommes enthousiastes de partager avec vous cette application et espérons qu'elle saura vous
                        plonger au cœur des mystères du Val Perdu !
                    </p>
                </div>
            </div>
        </div>

    </div>
</main>

<?php 
    include_once("./php/components/footer.php");
?>