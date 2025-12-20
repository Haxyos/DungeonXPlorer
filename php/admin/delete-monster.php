<?php
include_once "../components/security_admin.php";

$id = $_GET["id"] ?? '';

if (!$id) {
    header("Location: ./");
    exit;
}

$stmt = $db->prepare("SELECT name FROM Monster WHERE id = ?");
$stmt->execute([$id]);
$monster = $stmt->fetch();
$name = $monster['name'];

$stmt = $db->prepare("UPDATE Chapter set monster_id = null WHERE monster_id = ?");
$stmt->execute([$id]);

$stmt = $db->prepare("DELETE FROM Monster WHERE id = ?");
$stmt->execute([$id]);

$stmt = $db->prepare("DELETE FROM Monster_Loot WHERE monster_id = ?");
$stmt->execute([$id]);

header("Location: ./index.php?name=" . urlencode($name));
exit;

?>