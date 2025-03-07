<?php
// Désactiver l'affichage des erreurs pour éviter des sorties non JSON
ini_set('display_errors', 0);  // Désactive l'affichage des erreurs PHP
error_reporting(E_ALL);         // Active l'enregistrement des erreurs dans le fichier log

require_once __DIR__ . '/../vendor/autoload.php';
use Controllers\TaskController;

$controller = new TaskController();

// Vérifier l'action demandée et appeler la méthode correspondante
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'addTask':
            $controller->addTask();  // Appel de la méthode pour ajouter une tâche
            break;
        // Autres actions...
        default:
            // Retourner un JSON valide avec une erreur
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Action non reconnue.']);
            break;
    }
} else {
    // En cas de requête sans action
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Aucune action spécifiée.']);
}

?>
