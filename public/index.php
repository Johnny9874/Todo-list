<?php
ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs

echo "Début de l'exécution du fichier index.php.<br>";  // Message pour vérifier que le fichier est bien exécuté

require_once __DIR__ . '/../vendor/autoload.php';
use Controllers\UserController;

$controller = new UserController();

// Vérifier si l'action de connexion est demandée
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    echo "Appel à la méthode login() du contrôleur.<br>";  // Vérification de l'appel à la méthode
    $controller->login();
}

// Vérifier si l'action d'inscription est demandée
if (isset($_GET['action']) && $_GET['action'] === 'register') {
    echo "Appel à la méthode register() du contrôleur.<br>";  // Vérification de l'appel à la méthode
    $controller->register();
}
?>
