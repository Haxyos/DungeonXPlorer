<?php
    include_once("./php/components/header.php");
    $message = $_GET['message'] ?? '';
?>

<!-- 
    Structure principale avec l'image de fond fixe.
    Utilisation de flexbox pour aligner le contenu à gauche comme sur l'image de référence.
-->
<main class="flex-grow flex items-center relative min-h-screen bg-fixed bg-cover bg-center"
      style="background-image: url('/images/Fond_ecran.png');">

    <!-- Ombre portée plus forte sur la gauche pour faire ressortir le texte -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/50 to-transparent z-0"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center h-full">
        
        <!-- Bloc de contenu style "JdrCorner" (Gauche) -->
        <div class="w-full md:w-1/2 lg:w-5/12">
            
            <?php if ($message): ?>
                <div class="mb-6 p-4 text-center bg-green-900/80 border border-green-700 text-green-300 rounded-lg shadow-xl backdrop-blur-sm">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="bg-gray-900/60 backdrop-blur-md p-8 md:p-10 rounded-3xl border border-gray-700/50 shadow-2xl">
                <h1 class="text-5xl md:text-6xl font-extrabold text-[#f2a900] mb-6 leading-tight drop-shadow-md">
                    DungeonXplorer : <br>
                    <span class="text-white text-4xl md:text-5xl">Le Portail des Héros</span>
                </h1>
                
                <h2 class="text-xl font-bold text-gray-200 mb-4 uppercase tracking-wider">
                    Bienvenue sur votre gestionnaire de JDR ultime
                </h2>

                <p class="text-gray-300 text-lg leading-relaxed mb-8">
                    Que vous soyez Maître du Jeu ou aventurier novice, DungeonXplorer centralise vos fiches de personnages, vos campagnes et vos lancers de dés.
                    Plongez dans l'univers d'Erendor, découvrez l'aventure du <em>Val Perdu</em> et ne perdez plus jamais une note de session.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="./php/connection/register.php" 
                       class="px-8 py-3 rounded-full bg-[#f2a900] text-black font-bold text-lg hover:bg-yellow-400 transition transform hover:scale-105 shadow-lg text-center">
                        Commencer l'aventure
                    </a>
                    <a href="#more-info" 
                       class="px-8 py-3 rounded-full border-2 border-white text-white font-bold text-lg hover:bg-white hover:text-black transition transform hover:scale-105 shadow-lg text-center">
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>

        <!-- Espace vide à droite pour laisser voir l'illustration (l'œil du dragon ou la rune) -->
        <div class="hidden md:block md:w-1/2 lg:w-7/12 h-full flex items-center justify-center">
            <!-- Optionnel: Un bouton "Play" ou une icône flottante pour rappeler le style vidéo -->
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-full border border-white/20 shadow-[0_0_50px_rgba(242,169,0,0.3)] animate-pulse cursor-pointer hover:bg-white/20 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-80" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

    </div>
</main>

<!-- Section Contenu Secondaire (L'aventure du Val Perdu) -->
<section id="more-info" class="bg-[#0f0f0f] text-white py-16 px-6">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-[#f2a900] mb-8 border-b border-gray-800 pb-4">À la une : Le Val Perdu</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Carte Chapitre 1 -->
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 border border-gray-700 group">
                <div class="h-48 bg-gray-700 relative overflow-hidden">
                    <img src="https://placehold.co/600x400/222/f2a900?text=Le+Village" alt="Village" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-white group-hover:text-[#f2a900] transition">Chapitre 1: L'Appel</h3>
                    <p class="text-gray-400 text-sm mb-4">Le bourgmestre vous regarde avec désespoir. Sa fille a disparu dans la forêt maudite...</p>
                    <a href="#" class="text-[#f2a900] font-semibold hover:underline">Lire la suite &rarr;</a>
                </div>
            </div>

            <!-- Carte Monstres -->
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 border border-gray-700 group">
                <div class="h-48 bg-gray-700 relative overflow-hidden">
                    <img src="https://placehold.co/600x400/331111/ff5555?text=Menaces" alt="Monstres" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-white group-hover:text-red-500 transition">Bestiaire</h3>
                    <p class="text-gray-400 text-sm mb-4">Du Sanglier Enragé au Loup Noir, découvrez les créatures qui hantent ces bois.</p>
                    <a href="#" class="text-red-500 font-semibold hover:underline">Voir les fiches &rarr;</a>
                </div>
            </div>

             <!-- Carte Règles -->
             <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 border border-gray-700 group">
                <div class="h-48 bg-gray-700 relative overflow-hidden">
                    <img src="https://placehold.co/600x400/112233/88ccff?text=Règles" alt="Règles" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 text-white group-hover:text-blue-400 transition">Le Sacrifice</h3>
                    <p class="text-gray-400 text-sm mb-4">Une seconde chance est possible, mais le destin exigera un lourd tribut...</p>
                    <a href="#" class="text-blue-400 font-semibold hover:underline">Comprendre la mécanique &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
    include_once("./php/components/footer.php");
?>