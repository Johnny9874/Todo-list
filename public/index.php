<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclure les contrôleurs nécessaires
require_once __DIR__ . '/../src/controllers/UserController.php'; // Utilisez __DIR__ pour obtenir le chemin absolu

// Vérifiez l'action de la requête
if (isset($_GET['action']) && $_GET['action'] === 'register') {
    $controller = new UserController();
    $controller->register();
}
?>

