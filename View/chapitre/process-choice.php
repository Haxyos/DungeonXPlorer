<?php
session_start();
include_once '../../php/Database.php';

$nextChapterId = $_GET['chapter'] ?? '';
$heroId = $_GET['hero'] ?? '';
$userId = $_SESSION['user_id'] ?? '';

if (!$nextChapterId || !$heroId || !$userId) {
    header("Location: /index.php?error=missing_params");
    exit;
}

try {
    $stmt = $db->prepare("SELECT * FROM Hero_Progress WHERE hero_id = ? AND user_id = ?");
    $stmt->execute([$heroId, $userId]);
    $progress = $stmt->fetch();

    if (!$progress) {
        header("Location: /index.php?error=invalid_hero");
        exit;
    }

    $currentChapterId = $progress['chapter_id'];

    $stmt = $db->prepare("SELECT * FROM Links WHERE chapter_id = ? AND next_chapter_id = ?");
    $stmt->execute([$currentChapterId, $nextChapterId]);
    $validChoice = $stmt->fetch();

    if (!$validChoice) {
        header("Location: index.php?chapter=" . $currentChapterId . "&hero=" . $heroId . "&error=invalid_choice");
        exit;
    }

    $stmt = $db->prepare("UPDATE Hero_Progress SET chapter_id = ?, updated_at = NOW() WHERE hero_id = ? AND user_id = ?");
    $stmt->execute([$nextChapterId, $heroId, $userId]);

    header("Location: index.php?chapter=" . $nextChapterId . "&hero=" . $heroId);
    exit;

} catch (Exception $e) {
    error_log("Erreur progression chapitre: " . $e->getMessage());
    header("Location: /index.php?error=progression_failed");
    exit;
}
?>