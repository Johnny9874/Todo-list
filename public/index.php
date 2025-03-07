<?php
session_start();  // Démarre la session avant d'utiliser $_SESSION

ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs

require_once __DIR__ . '/../vendor/autoload.php'; // Ce fichier autoload permet de charger automatiquement les classes

// Si tu utilises des namespaces dans tes fichiers, il faut les importer ici
use Controllers\UserController;
use Controllers\TaskController;

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
            // Vérifier si l'utilisateur est connecté avant de procéder
            if (!isset($_SESSION['user_id'])) {
                // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
                header('Location: login.php');
                exit();
            }

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
