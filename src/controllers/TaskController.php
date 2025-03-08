<?php
namespace Controllers;

use Services\TaskService;  // Assurez-vous que le namespace est correct

class TaskController {
    private $taskService;

    public function __construct() {
        $this->taskService = new TaskService();
    }

    public function addTask() {
        try {
            // Lire les données JSON envoyées via le formulaire
            $data = json_decode(file_get_contents("php://input"), true);
            
            if ($data === null) {
                throw new Exception('Erreur dans le JSON envoyé : ' . json_last_error_msg());
            }
    
            // Afficher les données reçues pour vérification
            error_log("Données reçues dans addTask: " . print_r($data, true));
    
            // Vérification des données reçues
            if (!isset($data['title'], $data['description'], $data['priority'], $data['status'], $data['due_date'])) {
                throw new Exception('Les données requises sont manquantes');
            }
    
            // Traiter les données
            $title = $data['title'];
            $description = $data['description'];
            $priority = $data['priority'];
            $status = $data['status'];
            $due_date = $data['due_date'];
            $userId = $_SESSION['user_id'];  // Utiliser l'ID de la session
    
            // Ajouter la tâche
            $task = $this->taskService->addTask($title, $description, $userId, $priority, $status, $due_date, $data);
    
            // Loguer l'objet task renvoyé pour vérifier qu'il correspond à ce qu'on attend
            error_log("Tâche ajoutée : " . print_r($task, true));
    
            // Renvoyer la tâche ajoutée
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'task' => $task
            ]);
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    

    // Récupérer les tâches depuis MySQL
    public function getUserTasks() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Utilisateur non connecté.");
            }

            $userId = $_SESSION['user_id'];

            // Utiliser TaskService pour récupérer les tâches
            $tasks = $this->taskService->getTasksByUser($userId);

            // Retourner les tâches sous forme de JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'tasks' => $tasks
            ]);
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
?>
