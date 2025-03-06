<?php
ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs

require_once __DIR__ . '/../vendor/autoload.php';
use Controllers\UserController;

$controller = new UserController();

// Vérifier si l'action de connexion est demandée
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $controller->login();
}

?>
