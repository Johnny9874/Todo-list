<?php
require_once '../services/TaskService.php';

class TaskController {
    private $taskService;

    public function __construct() {
        $this->taskService = new TaskService();
    }

    // Ajouter une tâche
    public function addTask($title, $description, $userId) {
        try {
            $this->taskService->addTask($title, $description, $userId);
            echo "✅ Tâche ajoutée avec succès.";
        } catch (Exception $e) {
            echo "❌ Erreur : " . $e->getMessage();
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
