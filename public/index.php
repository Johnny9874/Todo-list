<?php
ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs

require_once __DIR__ . '/../vendor/autoload.php'; // Ceci doit être là pour charger l'autoloader de Composer


// Si tu utilises des namespaces dans tes fichiers, il faut les importer ici
use Controllers\UserController;
use Controllers\TaskController;  // Assurez-vous d'importer TaskController correctement

// Créer une instance de UserController
$userController = new UserController();

// Vérifier l'action demandée et appeler la méthode correspondante
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            $userController->login();
            break;
        case 'register':
            $userController->register();
            break;
        case 'addTask':
            $taskController = new TaskController();  // Création de l'objet TaskController
            $taskController->addTask();  // Appel de la méthode pour ajouter la tâche
            break;
        case 'updateProfile':
            $userController->updateProfile();  // Appel de la méthode pour mettre à jour le profil
            break;
        case 'profile':
            $userController->getProfile();  // Appel de la méthode qui gère le profil
            break;
        default:
            echo "Action non reconnue.<br>";
    }
}
?>
