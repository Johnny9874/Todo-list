<?php
ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs

echo "Début de l'exécution du fichier index.php.<br>";  // Message pour vérifier que le fichier est bien exécuté

require_once __DIR__ . '/../vendor/autoload.php';
use Controllers\UserController;

$controller = new UserController();

// Vérifier l'action demandée et appeler la méthode correspondante
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            echo "Appel à la méthode login() du contrôleur.<br>";
            $controller->login();
            break;
        case 'register':
            echo "Appel à la méthode register() du contrôleur.<br>";
            $controller->register();
            break;
        case 'updateProfile':
            echo "Appel à la méthode updateProfile() du contrôleur.<br>";
            $controller->updateProfile();  // Appel de la méthode pour mettre à jour le profil
            break;
        case 'profile':
            echo "Appel à la méthode getProfile() du contrôleur.<br>";
            $controller->getProfile();  // Appel de la méthode qui gère le profil
            break;
        default:
            echo "Action non reconnue.<br>";
    }
}
?>
