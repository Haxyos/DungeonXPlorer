<?php
include_once('../components/security_admin.php');
include_once('../components/header.php');

$id = $_GET['id'] ?? '';

if (!$id) {
    header("Location: ./index.php");
    exit;
}

$stmt = $db->prepare('SELECT * FROM Monster WHERE id = ?');
$stmt->execute([$id]);
$monster = $stmt->fetch();

if (!$monster) {
    header("Location: ./index.php");
    exit;
}
?>

<div class="container mx-auto px-4 mt-16 mb-16">
    <div class="max-w-3xl mx-auto">

        <div class="mb-8">
            <a href="./index.php" class="text-gray-400 hover:text-white transition-colors inline-flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour au dashboard
            </a>
            <h1 class="text-4xl font-bold text-white mb-2">Modifier le monstre</h1>
            <p class="text-gray-400"><?= htmlspecialchars($monster['name']) ?></p>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700">
            <form action="update-monster.php" method="POST" enctype="multipart/form-data" class="p-6">

                <input type="hidden" name="id" value="<?= $monster['id'] ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="nom" class="block text-sm font-semibold text-gray-300 mb-2">
                            Nom du monstre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nom" name="nom" required
                            value="<?= htmlspecialchars($monster['name']) ?>"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                            placeholder="Ex: Gobelin sauvage">
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-300 mb-2">
                            Image <span class="text-gray-500 text-xs">(optionnel)</span>
                        </label>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-[#941515] file:text-white file:cursor-pointer">
                        <?php if ($monster['image']): ?>
                            <p class="text-xs text-gray-500 mt-1">Image actuelle : <?= basename($monster['image']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="pv" class="block text-sm font-semibold text-gray-300 mb-2">
                            PV <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="pv" name="pv" required min="1"
                            value="<?= $monster['pv'] ?>"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                            placeholder="50">
                    </div>

                    <div>
                        <label for="mana" class="block text-sm font-semibold text-gray-300 mb-2">
                            Mana <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="mana" name="mana" required min="0"
                            value="<?= $monster['mana'] ?>"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                            placeholder="20">
                    </div>

                    <div>
                        <label for="initiative" class="block text-sm font-semibold text-gray-300 mb-2">
                            Initiative <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="initiative" name="initiative" required min="1"
                            value="<?= $monster['initiative'] ?>"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                            placeholder="10">
                    </div>

                    <div>
                        <label for="strength" class="block text-sm font-semibold text-gray-300 mb-2">
                            Force <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="strength" name="strength" required min="1"
                            value="<?= $monster['strength'] ?>"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                            placeholder="15">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="xp" class="block text-sm font-semibold text-gray-300 mb-2">
                            XP donné <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="xp" name="xp" required min="0"
                            value="<?= $monster['xp'] ?>"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                            placeholder="100">
                    </div>

                    <div>
                        <label for="hostilite" class="block text-sm font-semibold text-gray-300 mb-2">
                            Hostilité <span class="text-red-500">*</span>
                        </label>
                        <select id="hostilite" name="hostilite" required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all">
                            <option value="0" <?= $monster['Hostilité'] == 0 ? 'selected' : '' ?>>Pacifique (0)</option>
                            <option value="1" <?= $monster['Hostilité'] == 1 ? 'selected' : '' ?>>Neutre (1)</option>
                            <option value="2" <?= $monster['Hostilité'] == 2 ? 'selected' : '' ?>>Agressif (2)</option>
                            <option value="3" <?= $monster['Hostilité'] == 3 ? 'selected' : '' ?>>Très hostile (3)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="attack_text" class="block text-sm font-semibold text-gray-300 mb-2">
                        Texte d'attaque <span class="text-red-500">*</span>
                    </label>
                    <textarea id="attack_text" name="attack_text" rows="3" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all resize-none"
                        placeholder="Le gobelin vous frappe avec sa massue rouillée !"><?= htmlspecialchars($monster['attack']) ?></textarea>
                </div>

                <div class="flex gap-3 justify-end pt-4 border-t border-gray-700">
                    <a href="./index.php"
                        class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors font-semibold">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-[#941515] to-red-700 hover:from-red-700 hover:to-[#941515] text-white rounded-lg transition-all font-semibold shadow-lg">
                        Enregistrer les modifications
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<?php
include_once('../components/footer.php');
?>