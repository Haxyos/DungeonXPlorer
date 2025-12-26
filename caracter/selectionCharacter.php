<?php
session_start();

// 1. Charger la connexion BDD
// Adapte le chemin selon ton arborescence réelle
require_once __DIR__ . '/../php/Database.php';

// 2. Charger le Contrôleur
require_once __DIR__ . '/../Controller/HeroController.php';

// 3. Vérifier session (Redirection simple si pas connecté)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?message=Veuillez vous connecter");
    exit();
}

// 4. Lancer le MVC
$controller = new HeroController($db, $_SESSION['user_id']);
$controller->handleRequest();