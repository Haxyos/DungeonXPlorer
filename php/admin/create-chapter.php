<?php
include_once('../components/security_admin.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['titre'] ?? '';
        $content = $_POST['content'] ?? '';
        $ordre = $_POST['ordre'] ?? 1;
        $nextChapters = $_POST['next_chapters'] ?? [];

        $stmt = $db->prepare('INSERT INTO Chapter (id, titre, content) VALUES (?, ?, ?)');
        $stmt->execute([$ordre, $titre, $content]);

        if (!empty($nextChapters)) {
            foreach ($nextChapters as $nextChapterId) {
                $choiceTextKey = "choice_text_" . $nextChapterId;
                $choiceText = $_POST[$choiceTextKey] ?? '';

                if (!empty($choiceText)) {
                    $stmt = $db->prepare('
                        INSERT INTO Links (chapter_id, next_chapter_id, description) 
                        VALUES (?, ?, ?)
                    ');
                    $stmt->execute([$ordre, $nextChapterId, $choiceText]);
                }
            }
        }

        header('Location: dashboard.php?created=' . urlencode($titre));
    }
?>