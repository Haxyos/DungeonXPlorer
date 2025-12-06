<?php
include_once('../components/security_admin.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $titre = $_POST['titre'] ?? '';
    $content = $_POST['content'] ?? '';
    $nextChapters = $_POST['next_chapters'] ?? [];

    $stmt = $db->prepare('UPDATE Chapter SET titre = ?, content = ? WHERE id = ?');
    $stmt->execute([$titre, $content, $id]);
    $stmt = $db->prepare('DELETE FROM Links WHERE chapter_id = ?');
    $stmt->execute([$id]);

    if (!empty($nextChapters)) {
        foreach ($nextChapters as $nextChapterId) {
            $choiceTextKey = "choice_text_" . $nextChapterId;
            $choiceText = $_POST[$choiceTextKey] ?? '';

            if (!empty($choiceText)) {
                $stmt = $db->prepare('
                        INSERT INTO Links (chapter_id, next_chapter_id, description) 
                        VALUES (?, ?, ?)
                    ');
                $stmt->execute([$id, $nextChapterId, $choiceText]);
            }
        }
    }

    header('Location: index.php?updated=' . urlencode($titre));

}
?>