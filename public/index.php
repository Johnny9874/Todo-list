<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclure les contrôleurs nécessaires
require_once __DIR__ . '/autoload.php';


// Vérifiez l'action de la requête
if (isset($_GET['action'])) {
    // Créer une instance du contrôleur une seule fois
    $controller = new UserController();
    
    // Gérer les différentes actions
    switch ($_GET['action']) {
        case 'register':
            $controller->register();
            break;
        case 'login':
            $controller->login();
            break;
        case 'profile':
            $user = $controller->getProfile();
            if ($user) {
                include __DIR__ . '/html/profile.html';
            } else {
                echo "Utilisateur non trouvé";
            }
            break;
        case 'updateProfile':
            $controller->updateProfile();
            break;
        default:
            echo "Action non reconnue";
            break;
    }
}
