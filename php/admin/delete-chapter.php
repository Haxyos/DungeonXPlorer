<?php
include_once "../components/security_admin.php";

$id = $_GET["id"] ?? '';

if (!$id) {
    header("Location: ./");
    exit;
}

$stmt = $db->prepare("SELECT titre FROM Chapter WHERE id = ?");
$stmt->execute([$id]);
$chap = $stmt->fetch();

$titre = $chap['titre'];
$stmt = $db->prepare("DELETE FROM Links WHERE chapter_id = ?");
$stmt->execute([$id]);

$stmt = $db->prepare("DELETE FROM Links WHERE next_chapter_id = ?");
$stmt->execute([$id]);

$stmt = $db->prepare("DELETE FROM Chapter WHERE id = ?");
$stmt->execute([$id]);

header("Location: ./index.php?titre=" . urlencode($titre));
exit;

?>