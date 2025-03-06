<?php
ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs
ini_set('log_errors', 1);       // Activer la journalisation des erreurs
ini_set('error_log', '/tmp/php_errors.log');  // Spécifier où enregistrer les erreurs

session_start();
session_regenerate_id(true);

// Debug : Afficher message pour vérifier l'exécution
error_log("Index.php exécuté.");
echo "Début de l'exécution du script index.php.";  // Ajout d'un echo pour débogage

require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\UserController;

$controller = new UserController();

// Debug : Vérifier si l'instance du contrôleur est créée
error_log("UserController instance créée.");
echo "Contrôleur utilisateur instancié.";  // Ajout d'un echo pour débogage

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->register();
            } else {
                include __DIR__ . '/html/register.html';
            }
            break;

        default:
            $user = $controller->getProfile();
            if ($user) {
                include __DIR__ . '/html/main.html';
            } else {
                error_log("Utilisateur non connecté.");
                echo "Utilisateur non connecté.";  // Afficher un message pour savoir où l'exécution échoue
            }
            break;
    }
}

?>