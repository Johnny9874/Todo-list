<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclure les contrôleurs nécessaires
require_once __DIR__ . '/../src/controllers/UserController.php';

// Vérifiez l'action de la requête
if (isset($_GET['action'])) {
    $controller = new UserController();

    // Si l'action est "register", on appelle la méthode register
    if ($_GET['action'] === 'register') {
        $controller->register();
    }
    // Si l'action est "login", on appelle la méthode login
    elseif ($_GET['action'] === 'login') {
        $controller->login();
    }
}

if ($_GET['action'] === 'profile') {
    $controller = new UserController();
    $user = $controller->getProfile();

    if ($user) {
        include __DIR__ . '/html/profile.html';
    } else {
        echo "Utilisateur non trouvé";
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action === 'updateProfile') {
        $controller = new UserController();
        $controller->updateProfile();
    }
}