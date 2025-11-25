<?php
session_start();
include_once("../Database.php");
$userId = $_SESSION['user_id'];
if (!$userId) {
    header("Location: /index.php");
}
$stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user['est_admin']) {
    header("Location: /index.php");
}
