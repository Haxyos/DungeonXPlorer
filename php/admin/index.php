<?php
include_once('../components/security_admin.php');
include_once('../components/header.php');
?>

<div class="container mx-auto px-4 mt-16">
    <?php if (isset($_GET['username'])) { ?>
        <div id="notification" class="fixed top-4 right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 z-50 transform transition-all duration-300 translate-x-0 border border-green-400">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>L'utilisateur <strong><?= htmlspecialchars($_GET['username'] ?? 'inconnu') ?></strong> a bien été supprimé !</span>
            <button onclick="closeNotification()" class="ml-2 hover:bg-white/20 rounded-lg p-1 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <script>
            setTimeout(() => {
                closeNotification();
            }, 5000);

            function closeNotification() {
                const notification = document.getElementById('notification');
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    notification.remove();
                    window.history.replaceState({}, document.title, window.location.pathname);
                }, 300);
            }
        </script>
    <?php } ?>

    <div class="mb-8 items-center justify-center">
        <h1 class="text-5xl font-bold text-white text-center mb-2 drop-shadow-lg">Dashboard Admin</h1>
        <p class="text-gray-300 mt-2 text-center text-lg">Gérez les utilisateurs, chapitres et monstres</p>
    </div>

    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl mb-6 border border-gray-700">
        <div class="flex border-b border-gray-700">
            <button onclick="showTab('users')" id="tab-users" class="tab-button active px-6 py-4 font-semibold text-[#941515] border-b-2 border-[#941515] transition-all hover:bg-gray-700/50">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Utilisateurs
                </span>
            </button>
            <button onclick="showTab('chapters')" id="tab-chapters" class="tab-button px-6 py-4 font-semibold text-gray-400 hover:text-[#941515] transition-all hover:bg-gray-700/50">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Chapitres
                </span>
            </button>
            <button onclick="showTab('monsters')" id="tab-monsters" class="tab-button px-6 py-4 font-semibold text-gray-400 hover:text-[#941515] transition-all hover:bg-gray-700/50">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Monstres
                </span>
            </button>
        </div>
    </div>

    <div id="content-users" class="tab-content">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-6 border border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-white">Gestion des utilisateurs</h2>
                <a href="add-user.php" class="bg-gradient-to-r from-[#941515] to-red-700 hover:from-red-700 hover:to-[#941515] text-white px-6 py-3 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter un utilisateur
                    </span>
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-700/50 border-b-2 border-gray-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Nom d'utilisateur</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php
                        $res = $db->query('SELECT * FROM users');

                        while ($user = $res->fetch()) {
                            echo '<tr class="hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-300 font-medium">' . $user["id"] . '</td>
                                <td class="px-6 py-4 text-sm text-white font-semibold">' . htmlspecialchars($user["username"]) . '</td>
                                <td class="px-6 py-4 text-sm text-gray-400">' . htmlspecialchars($user["email"]) . '</td>
                                <td class="px-6 py-4 text-sm">';
                            
                            if ($user["est_admin"]) {
                                echo '<span class="px-3 py-1.5 bg-red-900/30 text-red-400 rounded-full text-xs font-semibold border border-red-500/30">Admin</span>';
                            } else {
                                echo '<span class="px-3 py-1.5 bg-blue-900/30 text-blue-400 rounded-full text-xs font-semibold border border-blue-500/30">Joueur</span>';
                            }

                            echo '</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="./delete-user.php?id=' . $user["id"] . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet utilisateur ?\')" class="text-red-400 hover:text-red-300 font-semibold transition-colors inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Supprimer
                                    </a>
                                </td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="content-chapters" class="tab-content hidden">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-6 border border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-white">Gestion des chapitres</h2>
                <a href="add-chapter.php" class="bg-gradient-to-r from-[#941515] to-red-700 hover:from-red-700 hover:to-[#941515] text-white px-6 py-3 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter un chapitre
                    </span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-gray-700/30 border border-gray-600 rounded-lg p-5 hover:shadow-xl hover:border-[#941515] transition-all transform hover:-translate-y-1">
                    <h3 class="font-bold text-xl mb-2 text-white">Chapitre 1: Le Début</h3>
                    <p class="text-gray-400 text-sm mb-4">Description courte du chapitre...</p>
                    <div class="flex gap-2">
                        <a href="edit-chapter.php?id=1" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition-colors font-semibold shadow-md">
                            Modifier
                        </a>
                        <a href="delete-chapter.php?id=1" onclick="return confirm('Êtes-vous sûr ?')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-2 rounded-lg transition-colors font-semibold shadow-md">
                            Supprimer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="content-monsters" class="tab-content hidden">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-6 border border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-white">Gestion des monstres</h2>
                <a href="add-monster.php" class="bg-gradient-to-r from-[#941515] to-red-700 hover:from-red-700 hover:to-[#941515] text-white px-6 py-3 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter un monstre
                    </span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-700/30 border border-gray-600 rounded-lg p-4 hover:shadow-xl hover:border-[#941515] transition-all transform hover:-translate-y-1">
                    <div class="bg-gray-600/50 h-32 rounded-lg mb-3 flex items-center justify-center border border-gray-500">
                        <span class="text-5xl">👾</span>
                    </div>
                    <h3 class="font-bold text-center mb-1 text-white text-lg">Gobelin</h3>
                    <p class="text-sm text-gray-400 text-center mb-3 font-semibold">PV: 50 | ATK: 10</p>
                    <div class="flex gap-2">
                        <a href="edit-monster.php?id=1" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg text-sm transition-colors font-semibold shadow-md">
                            Modifier
                        </a>
                        <a href="delete-monster.php?id=1" onclick="return confirm('Êtes-vous sûr ?')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-2 rounded-lg text-sm transition-colors font-semibold shadow-md">
                            Supprimer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'text-[#941515]', 'border-b-2', 'border-[#941515]');
            button.classList.add('text-gray-400');
        });

        document.getElementById('content-' + tabName).classList.remove('hidden');

        const activeButton = document.getElementById('tab-' + tabName);
        activeButton.classList.add('active', 'text-[#941515]', 'border-b-2', 'border-[#941515]');
        activeButton.classList.remove('text-gray-400');
    }
</script>

<?php
include_once('../components/footer.php');
?>