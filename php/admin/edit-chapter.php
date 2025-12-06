<?php
include_once('../components/security_admin.php');
include_once('../components/header.php');

$id = $_GET['id'] ?? '';

if (!$id) {
    header("Location: ./index.php");
    exit;
}

$stmt = $db->prepare('SELECT * FROM Chapter WHERE id = ?');
$stmt->execute([$id]);
$chapter = $stmt->fetch();

$stmt = $db->prepare('SELECT next_chapter_id, description FROM Links WHERE chapter_id = ?');
$stmt->execute([$id]);
$existingLinks = $stmt->fetchAll();

$linkedChapters = [];
foreach ($existingLinks as $link) {
    $linkedChapters[$link['next_chapter_id']] = $link['description'];
}
?>

<div class="container mx-auto px-4 mt-16 mb-16">
    <div class="max-w-3xl mx-auto">

        <div class="mb-8">
            <a href="./index.php"
                class="text-gray-400 hover:text-white transition-colors inline-flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour au dashboard
            </a>
            <h1 class="text-4xl font-bold text-white mb-2">Modifier le chapitre</h1>
            <p class="text-gray-400">Chapitre <?= $chapter['id'] ?> : <?= htmlspecialchars($chapter['titre']) ?></p>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl border border-gray-700">
            <form action="update-chapter.php" method="POST" class="p-6">

                <input type="hidden" name="id" value="<?= $chapter['id'] ?>">

                <div class="mb-4">
                    <label for="titre" class="block text-sm font-semibold text-gray-300 mb-2">
                        Titre du chapitre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="titre" name="titre" required
                        value="<?= htmlspecialchars($chapter['titre']) ?>"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                        placeholder="Ex: La Forêt Mystérieuse">
                </div>

                <div class="mb-4">
                    <label for="content" class="block text-sm font-semibold text-gray-300 mb-2">
                        Contenu du chapitre <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="8" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all resize-none"
                        placeholder="Racontez l'histoire de ce chapitre..."><?= htmlspecialchars($chapter['content']) ?></textarea>
                </div>

                <div class="mb-6">
                    <label for="ordre" class="block text-sm font-semibold text-gray-300 mb-2">
                        Numéro du chapitre <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="ordre" name="ordre" min="1" required value="<?= $chapter['id'] ?>"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                        readonly>
                    <p class="text-xs text-gray-500 mt-1">Le numéro du chapitre ne peut pas être modifié</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-300 mb-3">
                        Chapitres suivants
                        <span class="text-gray-500 font-normal text-xs ml-2">(Les choix que le joueur pourra
                            faire)</span>
                    </label>

                    <div class="bg-gray-700/30 border border-gray-600 rounded-lg p-4 max-h-96 overflow-y-auto">
                        <?php
                        $chapters = $db->query('SELECT * FROM Chapter WHERE id != ' . $id . ' ORDER BY id');
                        if ($chapters->rowCount() == 0):
                            echo '<p class="text-gray-400 text-sm text-center py-4">Aucun autre chapitre disponible</p>';
                        else: ?>
                            <div class="space-y-3" id="next-chapters-container">
                                <?php foreach ($chapters as $chap):
                                    $isLinked = isset($linkedChapters[$chap['id']]);
                                    $description = $isLinked ? $linkedChapters[$chap['id']] : '';
                                    ?>
                                    <div
                                        class="choice-item bg-gray-700/50 rounded-lg border <?= $isLinked ? 'border-[#941515] bg-gray-700/70' : 'border-transparent' ?> transition-all">
                                        <label class="flex items-start p-3 cursor-pointer">
                                            <input type="checkbox" name="next_chapters[]" value="<?= $chap['id']; ?>"
                                                <?= $isLinked ? 'checked' : '' ?>
                                                onchange="toggleChoiceText(this, <?= $chap['id']; ?>)"
                                                class="w-5 h-5 text-[#941515] bg-gray-600 border-gray-500 rounded focus:ring-[#941515] focus:ring-2 cursor-pointer mt-1">
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center">
                                                    <span class="text-white font-semibold">
                                                        Chapitre <?= $chap['id']; ?>
                                                    </span>
                                                    <span class="text-gray-400 ml-2">
                                                        — <?= htmlspecialchars($chap['titre']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </label>

                                        <div id="choice-text-<?= $chap['id']; ?>"
                                            class="<?= $isLinked ? '' : 'hidden' ?> px-3 pb-3 pt-0">
                                            <label class="block text-xs text-gray-400 mb-2 ml-8">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                                Texte du choix (ce que le joueur verra)
                                            </label>
                                            <input type="text" name="choice_text_<?= $chap['id']; ?>"
                                                value="<?= htmlspecialchars($description) ?>"
                                                placeholder="Ex: Vous entrez dans la grotte sombre..."
                                                class="w-full ml-8 px-4 py-2 bg-gray-600 border border-gray-500 rounded-lg text-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#941515] focus:border-transparent transition-all"
                                                <?= $isLinked ? '' : 'disabled' ?>>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
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

<script>
    function toggleChoiceText(checkbox, chapterId) {
        const textContainer = document.getElementById(`choice-text-${chapterId}`);
        const textInput = textContainer.querySelector('input');
        const parentItem = checkbox.closest('.choice-item');

        if (checkbox.checked) {
            textContainer.classList.remove('hidden');
            textInput.disabled = false;
            textInput.required = true;
            parentItem.classList.add('border-[#941515]', 'bg-gray-700/70');
        } else {
            textContainer.classList.add('hidden');
            textInput.disabled = true;
            textInput.required = false;
            textInput.value = '';
            parentItem.classList.remove('border-[#941515]', 'bg-gray-700/70');
        }
    }
</script>

<?php
include_once('../components/footer.php');
?>