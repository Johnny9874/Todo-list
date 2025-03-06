<?php
ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs
ini_set('log_errors', 1);       // Activer la journalisation des erreurs
ini_set('error_log', '/tmp/php_errors.log');  // Spécifier où enregistrer les erreurs

session_start();
session_regenerate_id(true);

// Inclure le fichier d'autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Utiliser le namespace approprié pour UserController
use Controllers\UserController;

// Créer une instance de UserController
$controller = new UserController();

// Vérifier l'action de la requête
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Inscription
                $controller->register();
            } else {
                // Afficher le formulaire d'inscription
                include __DIR__ . '/html/register.html';
            }
            break;

        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Connexion
                $controller->login();
            } else {
                // Afficher le formulaire de connexion
                include __DIR__ . '/html/login.html';
            }
            break;

        case 'profile':
            // Afficher le profil de l'utilisateur
            $user = $controller->getProfile();
            if ($user) {
                include __DIR__ . '/html/profile.html';
            } else {
                echo "Utilisateur non trouvé ou non connecté.";
            }
            break;

        case 'updateProfile':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Mise à jour du profil
                $controller->updateProfile();
            }
            break;

        case 'logout':
            // Déconnexion
            session_start();
            session_destroy();
            header("Location: login.html");
            exit();
            break;

        default:
            // Afficher la page principale (to-do list)
            $user = $controller->getProfile();
            if ($user) {
                include __DIR__ . '/html/main.html';
            } else {
                echo "Utilisateur non connecté.";
            }
            break;
    }
}
?>
