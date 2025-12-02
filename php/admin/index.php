<?php
//include_once('../components/security_admin.php');
include_once('../components/header.php');
$password = "Tana";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4">
        
        <!-- Titre du dashboard -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Dashboard Admin</h1>
            <p class="text-gray-600 mt-2">Gérez les utilisateurs, chapitres et monstres</p>
        </div>

        <!-- Onglets de navigation -->
        <div class="bg-white rounded-lg shadow-lg mb-6">
            <div class="flex border-b border-gray-200">
                <button onclick="showTab('users')" id="tab-users" class="tab-button active px-6 py-4 font-semibold text-[#941515] border-b-2 border-[#941515] transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Utilisateurs
                    </span>
                </button>
                <button onclick="showTab('chapters')" id="tab-chapters" class="tab-button px-6 py-4 font-semibold text-gray-600 hover:text-[#941515] transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Chapitres
                    </span>
                </button>
                <button onclick="showTab('monsters')" id="tab-monsters" class="tab-button px-6 py-4 font-semibold text-gray-600 hover:text-[#941515] transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Monstres
                    </span>
                </button>
            </div>
        </div>

        <!-- Contenu des onglets -->
        
        <!-- Onglet Utilisateurs -->
        <div id="content-users" class="tab-content">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Gestion des utilisateurs</h2>
                    <a href="add-user.php" class="bg-[#941515] hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        + Ajouter un utilisateur
                    </a>
                </div>
                
                <!-- Tableau des users -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nom d'utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Rôle</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            // Ici tu mettras ton code pour récupérer les users
                            // Exemple fictif :
                            // $users = User::getAll();
                            // foreach ($users as $user):
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">1</td>
                                <td class="px-6 py-4 text-sm text-gray-900">Exemple User</td>
                                <td class="px-6 py-4 text-sm text-gray-600">user@example.com</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Joueur</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="edit-user.php?id=1" class="text-blue-600 hover:text-blue-800 mr-3">Modifier</a>
                                    <a href="delete-user.php?id=1" onclick="return confirm('Êtes-vous sûr ?')" class="text-red-600 hover:text-red-800">Supprimer</a>
                                </td>
                            </tr>
                            <?php // endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Onglet Chapitres -->
        <div id="content-chapters" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Gestion des chapitres</h2>
                    <a href="add-chapter.php" class="bg-[#941515] hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        + Ajouter un chapitre
                    </a>
                </div>
                
                <!-- Liste des chapitres -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php
                    // $chapters = Chapter::getAll();
                    // foreach ($chapters as $chapter):
                    ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h3 class="font-bold text-lg mb-2">Chapitre 1: Le Début</h3>
                        <p class="text-gray-600 text-sm mb-4">Description courte du chapitre...</p>
                        <div class="flex gap-2">
                            <a href="edit-chapter.php?id=1" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded transition-colors">
                                Modifier
                            </a>
                            <a href="delete-chapter.php?id=1" onclick="return confirm('Êtes-vous sûr ?')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-2 rounded transition-colors">
                                Supprimer
                            </a>
                        </div>
                    </div>
                    <?php // endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Onglet Monstres -->
        <div id="content-monsters" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Gestion des monstres</h2>
                    <a href="add-monster.php" class="bg-[#941515] hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        + Ajouter un monstre
                    </a>
                </div>
                
                <!-- Grille des monstres -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php
                    // $monsters = Monster::getAll();
                    // foreach ($monsters as $monster):
                    ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="bg-gray-200 h-32 rounded mb-3 flex items-center justify-center">
                            <span class="text-4xl">👾</span>
                        </div>
                        <h3 class="font-bold text-center mb-1">Gobelin</h3>
                        <p class="text-sm text-gray-600 text-center mb-2">PV: 50 | ATK: 10</p>
                        <div class="flex gap-2">
                            <a href="edit-monster.php?id=1" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-1 rounded text-sm transition-colors">
                                Modifier
                            </a>
                            <a href="delete-monster.php?id=1" onclick="return confirm('Êtes-vous sûr ?')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-1 rounded text-sm transition-colors">
                                Supprimer
                            </a>
                        </div>
                    </div>
                    <?php // endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function showTab(tabName) {
    // Cache tous les contenus
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Retire le style actif de tous les boutons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'text-[#941515]', 'border-b-2', 'border-[#941515]');
        button.classList.add('text-gray-600');
    });
    
    // Affiche le contenu sélectionné
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Active le bouton sélectionné
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.add('active', 'text-[#941515]', 'border-b-2', 'border-[#941515]');
    activeButton.classList.remove('text-gray-600');
}
</script>

<?php
include_once('../components/footer.php');
?>