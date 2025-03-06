<?php
ini_set('display_errors', 1);  // Afficher les erreurs PHP
error_reporting(E_ALL);         // Afficher toutes les erreurs

echo "Début du test du contrôleur.<br>";  // Message de débogage

require_once __DIR__ . '/../vendor/autoload.php';
use Controllers\UserController;

$controller = new UserController();

echo "Contrôleur instancié.<br>";  // Message de débogage

// Test de la méthode 'getProfile' sans inclure de vue
$user = $controller->getProfile();

echo "Test du profil terminé.<br>";  // Message de débogage

if ($user) {
    echo "Utilisateur connecté : " . $user['username'];  // Affichage d'un attribut utilisateur
} else {
    echo "Aucun utilisateur connecté.";
}
?>
