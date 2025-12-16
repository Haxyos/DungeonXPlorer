<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_hero_id'])) {
    $heroIdToDelete = intval($_POST['delete_hero_id']) ?? '';

    if (isset($heroIdToDelete)) {
        try {
            $stmt = $db->prepare("DELETE FROM Hero WHERE id = :id AND user_id = :user_id");
            $stmt->execute([
                ':id' => $heroIdToDelete,
                ':user_id' => $userId
            ]);

            if ($stmt->rowCount() > 0) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $error = "Impossible de supprimer le personnage (ou vous n'êtes pas son propriétaire).";
            }
        } catch (PDOException $e) {
            $error = "Erreur de suppression : " . $e->getMessage();
        }
    }
}

?>