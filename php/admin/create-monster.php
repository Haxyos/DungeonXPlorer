<?php
include_once('../components/security_admin.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nom = $_POST['nom'] ?? '';
        $pv = $_POST['pv'] ?? 0;
        $mana = $_POST['mana'] ?? 0;
        $initiative = $_POST['initiative'] ?? 0;
        $strength = $_POST['strength'] ?? 0;
        $attack_text = $_POST['attack_text'] ?? '';
        $xp = $_POST['xp'] ?? 0;
        $hostilite = $_POST['hostilite'] ?? 1;

        if (empty($nom) || empty($attack_text)) {
            header('Location: index.php?error=missing_fields');
            exit();
        }

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../images/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = uniqid('monster_') . '.' . $fileExtension;
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = '/images/' . $fileName;
                }
            }
        }

        // Insérer le monstre dans la base de données
        $stmt = $db->prepare('
            INSERT INTO Monster (name, pv, mana, initiative, strength, attack, xp, Hostilité, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $nom, 
            $pv, 
            $mana, 
            $initiative, 
            $strength, 
            $attack_text, 
            $xp, 
            $hostilite,
            $imagePath
        ]);

        // Rediriger vers le dashboard avec message de succès
        header('Location: index.php?monster_created=' . urlencode($nom));
        exit();
} else {
    // Si pas de POST, rediriger vers le dashboard
    header('Location: index.php');
    exit();
}
?>