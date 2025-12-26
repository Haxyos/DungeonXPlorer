<?php
include_once('../components/security_admin.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $pv = $_POST['pv'] ?? 0;
    $mana = $_POST['mana'] ?? 0;
    $initiative = $_POST['initiative'] ?? 0;
    $strength = $_POST['strength'] ?? 0;
    $attack_text = $_POST['attack_text'] ?? '';
    $xp = $_POST['xp'] ?? 0;
    $hostilite = $_POST['hostilite'] ?? 1;

    if (empty($id) || empty($nom) || empty($attack_text)) {
        header('Location: edit-monster.php?id=' . $id);
        exit();
    }

    $stmt = $db->prepare('SELECT image FROM Monster WHERE id = ?');
    $stmt->execute([$id]);
    $currentMonster = $stmt->fetch();
    $imagePath = $currentMonster['image'];

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
                if ($imagePath && file_exists('../../' . $imagePath)) {
                    unlink('../../' . $imagePath);
                }
                $imagePath = '/images/' . $fileName;
            }
        }
    }

    $stmt = $db->prepare('
            UPDATE Monster 
            SET name = ?, pv = ?, mana = ?, initiative = ?, strength = ?, attack = ?, xp = ?, Hostilité = ?, image = ?
            WHERE id = ?
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
        $imagePath,
        $id
    ]);

    header('Location: index.php?monster_updated=' . urlencode($nom));
    exit();

} else {
    header('Location: index.php');
    exit();
}
?>