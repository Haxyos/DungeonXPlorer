<?php
include_once('../components/security_admin.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre = trim($_POST['titre'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $ordre = intval($_POST['ordre'] ?? 0);
    $next_chapters = $_POST['next_chapters'] ?? [];
    $prev_chapters = $_POST['prev_chapters'] ?? [];

    if (empty($titre) || empty($content) || $ordre <= 0) {
        $_SESSION['error'] = "Le titre, le contenu et le numéro du chapitre sont obligatoires.";
        header('Location: ./');
        exit();
    }
    $db->beginTransaction();

    $stmt = $db->prepare("
            INSERT INTO Chapter (id, titre, content) 
            VALUES (:ordre, :titre, :content)
        ");
    $stmt->execute([
        ':titre' => $titre,
        ':content' => $content,
        ':ordre' => $ordre
    ]);

    if (!empty($next_chapters)) {
        $stmtLink = $db->prepare("
                INSERT INTO Links (chapter_id, next_chapter_id) 
                VALUES (:chapter_id, :next_chapter_id)
            ");

        foreach ($next_chapters as $next_id) {
            $stmtLink->execute([
                ':chapter_id' => $ordre,
                ':next_chapter_id' => intval($next_id)
            ]);
        }
    }

    if (!empty($prec_chapters)) {
        $stmtLink = $db->prepare("
                INSERT INTO Links (chapter_id, next_chapter_id) 
                VALUES (:chapter_id, :next_chapter_id)
            ");

        foreach ($prev_chapters as $prev_id) {
            $stmtLink->execute([
                ':chapter_id' => intval($prev_id),
                ':next_chapter_id' => $ordre
            ]);
        }
    }

    $db->commit();

    $_SESSION['success'] = "Le chapitre \"$titre\" a été créé avec succès !";


    header('Location: ./');
    exit();
} else {
    header('Location: ./');
    exit();
}
