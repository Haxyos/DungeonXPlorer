<?php

include_once "../components/security_admin.php";
$id = $_GET["id"] ?? '';
if (!$id) {
    header("Location: ./");
    exit;
}
$res = $db->query("Select titre FROM Chapter WHERE id=$id");
$chap = $res->fetch();
$titre = $chap['titre'];
$db->query("DELETE FROM Chapter WHERE id=$id");
header("Location: ./index.php?titre=$titre");
exit;
