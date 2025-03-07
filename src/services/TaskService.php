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
    
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            throw new Exception("Utilisateur non connecté.");
        }
    
        // Récupère l'ID de l'utilisateur connecté depuis la session
        $userId = $_SESSION['user_id'];
    
        // Vérifier si l'utilisateur existe
        $sql = "SELECT id FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            throw new Exception("L'utilisateur avec l'ID {$userId} n'existe pas.");
        }
    
        // Encoder correctement les données task_data en JSON
        $encoded_task_data = json_encode($task_data);
        
        // Vérifier si l'encodage JSON a réussi
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur lors de l\'encodage des données en JSON: ' . json_last_error_msg());
        }
    
        // L'utilisateur existe, on peut insérer la tâche
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
    
        // Récupérer l'ID de la tâche insérée
        $taskId = $stmt->insert_id;
        $stmt->close();
        
        // Récupérer la tâche ajoutée
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
