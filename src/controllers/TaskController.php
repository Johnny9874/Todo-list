<?php
require_once '../services/TaskService.php';

class TaskController {
    private $taskService;

    public function __construct() {
        $this->taskService = new TaskService();
    }

    public function addTask() {
        session_start();
    
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit();
        }
    
        // Récupérer les données envoyées via la requête POST
        $data = json_decode(file_get_contents('php://input'), true);  // Récupérer les données JSON envoyées
    
        if (empty($data['title']) || empty($data['description'])) {
            echo json_encode(['error' => 'Title and description are required']);
            exit();
        }
    
        // Extraire les données
        $title = $data['title'];
        $description = $data['description'];
        $userId = $_SESSION['user_id'];
        $priority = isset($data['priority']) ? $data['priority'] : 'low';
        $status = isset($data['status']) ? $data['status'] : 'pending';
        $due_date = isset($data['due_date']) ? $data['due_date'] : null;
        $task_data = json_encode($data['task_data']);  // Encoding des données JSON supplémentaires
    
        try {
            // Appeler la méthode du service pour ajouter la tâche
            $this->taskService->addTask($title, $description, $userId, $priority, $status, $due_date, $task_data);
            echo json_encode(['success' => 'Task added successfully']);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error adding task: ' . $e->getMessage()]);
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
