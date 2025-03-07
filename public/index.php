<?php
ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs

require_once __DIR__ . '/../vendor/autoload.php';

// Si tu utilises des namespaces dans tes fichiers, il faut les importer ici
use Controllers\UserController;
use Controllers\TaskController;  // Assure-toi d'importer TaskController correctement

$controller = new UserController();

// Vérifier l'action demandée et appeler la méthode correspondante
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            $controller->login();
            break;
        case 'register':
            $controller->register();
            break;
        case 'addTask':
            $controller->addTask();  // Assurez-vous que cette ligne est correcte
            break;
        case 'updateProfile':
            $controller->updateProfile();  // Appel de la méthode pour mettre à jour le profil
            break;
        case 'profile':
            $controller->getProfile();  // Appel de la méthode qui gère le profil
            break;
        case 'addTask':
            $taskController = new TaskController();  // Création de l'objet TaskController
            $taskController->addTask();  // Appel de la méthode pour ajouter la tâche
            break;
        default:
            echo "Action non reconnue.<br>";
    }
}
?>
