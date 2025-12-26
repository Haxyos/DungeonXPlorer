<?php

include_once "../components/security_admin.php";
$id = $_GET["id"] ?? '';
if (!$id) {
    header("Location: /index.php");
    exit;
}
$res = $db->query("Select username FROM users WHERE id=$id");
$user = $res->fetch();
$username = $user['username'];
$db->query("DELETE FROM users WHERE id=$id");
header("Location: ./index.php?username=$username");
exit;
