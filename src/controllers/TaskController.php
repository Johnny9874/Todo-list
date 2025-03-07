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
            // Lire les données JSON
            $data = json_decode(file_get_contents("php://input"), true);
            
            if ($data === null) {
                throw new Exception('Erreur dans le JSON envoyé : ' . json_last_error_msg());
            }
    
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
            $userId = 1;  // ID de l'utilisateur
    
            // Ajouter la tâche
            $taskId = $this->taskService->addTask($title, $description, $userId, $priority, $status, $due_date, json_encode($data));
    
            // Récupérer la tâche ajoutée
            $task = $this->taskService->getTaskById($taskId);
    
            // Retourner la tâche en format JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'task' => $task
            ]);
        } catch (Exception $e) {
            error_log("Erreur : " . $e->getMessage()); // Log de l'erreur
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    
    
    
    
    
    // Récupérer les tâches depuis MySQL
    public function getTasks($userId) {
        try {
            // Appeler directement MySQL pour récupérer les tâches
            $tasks = $this->taskService->getTasksByUser($userId);
            
            // Vérifie si des tâches existent
            if (empty($tasks)) {
                echo "📭 Aucune tâche trouvée.";
                return;
            }

            // Affichage sous forme JSON
            header('Content-Type: application/json');
            echo json_encode($tasks);
        } catch (Exception $e) {
            echo "❌ Erreur : " . $e->getMessage();
        }
    }

    // Supprimer une tâche
    public function deleteTask($taskId) {
        try {
            $this->taskService->deleteTask($taskId);
            echo "🗑️ Tâche supprimée avec succès.";
        } catch (Exception $e) {
            echo "❌ Erreur : " . $e->getMessage();
        }
    }
}
?>
