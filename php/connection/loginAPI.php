<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../Database.php';

if(isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $email = trim($_GET['email'] ?? '');
    $password = $_GET['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $message = "Email ou mot de passe invalide.";
        header("Location: ./login.php?message_error=" . urlencode($message));
        exit;
    } else {
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['motDePasse'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_mail'] = $user['email'];
            header("Location: /index.php");
            exit;
        } else {
            $message = "Email ou mot de passe invalide.";
            header("Location: ./login.php?message_error=" . urlencode($message));
            exit;
        }
    }
}
?>