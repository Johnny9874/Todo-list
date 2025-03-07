<?php
namespace Services;

use Dao\TaskDAO; // Assurez-vous du bon namespace de TaskDAO
use Exception;

class TaskService {
    private $taskDAO;  // Gestion MySQL

    public function __construct() {
        $this->taskDAO = new TaskDAO();  // Instancier MySQL
    }

    public function addTask($title, $description, $userId, $priority, $status, $due_date, $task_data) {
        global $conn;
    
        // Encoder correctement les données task_data en JSON
        $encoded_task_data = json_encode($task_data);
    
        // Vérifier si l'encodage JSON a réussi
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur lors de l\'encodage des données en JSON: ' . json_last_error_msg());
        }
    
        // Insérer la tâche dans la base de données
        $sql = "INSERT INTO tasks (title, description, user_id, priority, status, due_date, task_data) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            die("Error preparing the query: " . $conn->error);
        }
    
        // Insérer la tâche avec task_data encodé
        $stmt->bind_param("sssisss", $title, $description, $userId, $priority, $status, $due_date, $encoded_task_data);
    
        if (!$stmt->execute()) {
            die("Error executing the query: " . $stmt->error);
        }
    
        $taskId = $stmt->insert_id;  // Récupérer l'ID de la tâche insérée
        $stmt->close();
    
        return $this->getTaskById($taskId);  // Retourner la tâche ajoutée
    }
    
    
    

    // Récupérer les tâches de MySQL
    public function getTasksByUser($userId) {
        return $this->taskDAO->getTasksByUser($userId);  // Appel uniquement MySQL
    }

    // Supprimer une tâche en MySQL
    public function deleteTask($taskId) {
        // Appel à TaskDAO pour obtenir la tâche
        $task = $this->taskDAO->getTaskById($taskId);
        if (!$task) {
            throw new Exception("Tâche non trouvée.");
        }

        // Supprimer en MySQL
        $this->taskDAO->deleteTask($taskId);
    }
    // Dans Services\TaskService.php
    public function getTaskById($taskId) {
        return $this->taskDAO->getTaskById($taskId); // Assurez-vous que la méthode existe dans TaskDAO
    }

}
?>
