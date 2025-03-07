<?php
require_once dirname(__DIR__) . '/services/TaskService.php'; // Utilisation de dirname pour s'assurer du bon chemin

class TaskController {
    private $taskService;

    public function __construct() {
        $this->taskService = new TaskService();
    }

    public function addTask() {
        try {
            // Lire les données JSON envoyées
            $data = json_decode(file_get_contents("php://input"), true);
    
            // Log des données reçues pour débogage
            error_log("Données reçues : " . print_r($data, true));
    
            // Vérification si les champs sont présents
            if (!isset($data['title'], $data['description'], $data['priority'], $data['status'], $data['due_date'])) {
                throw new Exception('Les données requises sont manquantes');
            }
    
            // Extraire les données
            $title = $data['title'];
            $description = $data['description'];
            $priority = $data['priority'];
            $status = $data['status'];
            $due_date = $data['due_date'];
            $userId = 1;  // Id de l'utilisateur, tu devrais probablement utiliser la session ici
    
            // Ajouter la tâche à la base de données via le service
            $taskId = $this->taskService->addTask($title, $description, $userId, $priority, $status, $due_date, json_encode($data));
    
            // Récupérer la tâche ajoutée
            $task = $this->taskService->getTaskById($taskId);
    
            // Log des données de la tâche ajoutée pour débogage
            error_log("Tâche ajoutée : " . print_r($task, true));
    
            // Répondre avec la tâche ajoutée
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'task' => $task
            ]);
        } catch (Exception $e) {
            // Log des erreurs pour débogage
            error_log("Erreur : " . $e->getMessage());
    
            // Répondre avec une erreur JSON
            header('Content-Type: application/json');
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
