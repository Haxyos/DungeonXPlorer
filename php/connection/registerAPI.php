<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

include("../Database.php");

function redirect_on_error($message) {
    header('Location: ./register.php?message=' . urlencode($message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        redirect_on_error("Tous les champs sont requis pour l'enregistrement.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         redirect_on_error("Le Sceau du Domaine (email) n'est pas au bon format.");
    }
    
    if ($password !== $confirm_password) {
        redirect_on_error("Les Clés de la Crypte (mots de passe) ne correspondent pas.");
    }
    
    if (strlen($password) < 8) {
        redirect_on_error("La Clé de la Crypte doit contenir au moins 8 caractères.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $db->prepare($sql);

    if ($stmt->execute(['email' => $email]) && $stmt->rowCount() > 0) {
        redirect_on_error("Ce Sceau du Domaine (email) est déjà enrôlé dans la Guilde.");
    } else {
        $sql = "INSERT INTO users (username, motDePasse, email)
                VALUES (:username, :mdp, :email)";
        $stmt = $db->prepare($sql);

        if ($stmt->execute([
            'email' => $email,
            'mdp' => $hashed_password,
            'username' => $username,
        ])) {
            header("Location: ./login.php?message_success=" . urlencode("Héros '{$username}' créé avec succès ! Connectez-vous pour commencer l'aventure."));
            exit;
        } else {
            redirect_on_error("Échec de la cérémonie d'enrôlement. Erreur lors de la création du Héros.");
        }
    }
} else {
    redirect_on_error("Requête invalide.");
}
?>