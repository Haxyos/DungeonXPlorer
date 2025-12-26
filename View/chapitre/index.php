<?php
require_once '../../Controller/ChapterController.php';
include_once '../../php/Database.php';

//$chapterId = $_GET["chapter"] ?? '';
$userId = $_SESSION['user_id'] ?? '';
$heroId = $_GET["hero"] ?? '';

if (/*!$chapterId || */!$userId || !$heroId) {
    header("Location: /index.php");
    exit;
}

$req = $db->prepare("SELECT * FROM Hero_Progress JOIN Hero ON Hero.id = Hero_Progress.hero_id WHERE Hero.user_id = ? AND hero_id = ?");
$req->execute([$userId, $heroId]);
$userHero = $req->fetch();

if (!$userHero) {
    header("Location: /index.php");
    exit;
}

$chapterId = $userHero["chapter_id"];
/*
if ($userHero["chapter_id"] != $chapterId) {
    header("Location: index.php?chapter=" . $userHero["chapter_id"] . "&hero=" . $heroId);
    exit;
}
*/

$chapterController = new ChapterController();
$chapter = $chapterController->getChapter($chapterId);

if (!$chapter) {
    header("Location: /index.php?error=chapter_not_found");
    exit;
}

include_once '../../php/components/header.php';
?>
 <main class="pt-32 pb-12 px-6 min-h-screen bg-[#1A1A1A] text-white font-sans">
    <div class="mb-6 bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg p-4 border border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-[#941515] rounded-full flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Héros</p>
                <p class="text-white font-bold"><?php echo htmlspecialchars($userHero['name'] ?? 'Aventurier'); ?></p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-400">Chapitre</p>
            <p class="text-white font-bold"><?php echo htmlspecialchars($chapterId); ?></p>
        </div>
    </div>

    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-4 text-center">
            <?php echo htmlspecialchars($chapter->getTitle()); ?>
        </h1>
    </div>

    <!--
    <?php /*if ($chapter->getImage()): ?>
        <div class="mb-8 rounded-xl overflow-hidden shadow-2xl border border-gray-700">
            <img
                src="<?php echo htmlspecialchars($chapter->getImage()); ?>"
                alt="<?php echo htmlspecialchars($chapter->getTitle()); ?>"
                class="w-full h-auto">
        </div>
    <?php endif; */?>-->

    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-6 border border-gray-700 mb-8">
        <div class="text-gray-300 leading-relaxed text-lg whitespace-pre-line">
            <?php echo htmlspecialchars($chapter->getDescription()); ?>
        </div>
    </div>

    <?php if (!empty($chapter->getChoices())): ?>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-6 border border-gray-700">
            <h2 class="text-2xl font-bold text-white mb-4">
                <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                </svg>
                Choisissez votre chemin
            </h2>

            <div class="space-y-3">
                <?php foreach ($chapter->getChoices() as $choice): ?>
                    <a href="process-choice.php?chapter=<?php echo htmlspecialchars($choice['chapter']); ?>&hero=<?php echo htmlspecialchars($heroId); ?>"
                        class="block bg-gray-700/50 hover:bg-[#941515] border border-gray-600 hover:border-[#941515] rounded-lg p-4 transition-all transform hover:-translate-y-1 hover:shadow-xl group">
                        <span class="text-white font-semibold text-lg flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            <?php echo htmlspecialchars($choice['text']); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-2xl p-6 border border-gray-700 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-[#941515]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-white mb-2">Fin du chapitre</h2>
            <p class="text-gray-400 mb-4">Vous avez atteint la fin de cette histoire.</p>
            <a href="/index.php" class="inline-block bg-gradient-to-r from-[#941515] to-red-700 hover:from-red-700 hover:to-[#941515] text-white px-6 py-3 rounded-lg transition-all font-semibold shadow-lg">
                Retour à l'accueil
            </a>
        </div>
    <?php endif; ?>
</main>
<?php include_once '../../php/components/footer.php'; ?>
