<?php
namespace Services;

use Dao\TaskDAO; // Assurez-vous du bon namespace de TaskDAO
use Exception;

class TaskService {
    private $taskDAO;  // Gestion MySQL

    public function __construct() {
        $this->taskDAO = new TaskDAO();  // Instancier MySQL
    }

    public function addTask($title, $description, $priority, $status, $due_date, $task_data) {
        session_start();  // Démarre la session pour récupérer l'ID utilisateur
        
        // Vérifier si l'utilisateur est connecté (si la variable de session existe)
        if (!isset($_SESSION['user_id'])) {
            throw new Exception("Utilisateur non connecté.");
        }
        
        // Récupérer l'ID utilisateur depuis la session
        $userId = $_SESSION['user_id'];
        
        global $conn;
        
        // Vérifier si l'utilisateur existe dans la base de données
        $sql = "SELECT id FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("L'utilisateur avec l'ID {$userId} n'existe pas.");
        }
    
        // L'utilisateur existe, on peut insérer la tâche
        $sql = "INSERT INTO tasks (title, description, user_id, priority, status, due_date, task_data) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            die("Error preparing the query: " . $conn->error);
        }
    
        $stmt->bind_param("sssisss", $title, $description, $userId, $priority, $status, $due_date, $task_data);
    
        if (!$stmt->execute()) {
            die("Error executing the query: " . $stmt->error);
        }
    
        $stmt->close();
    }

    // Récupérer les tâches de MySQL
    public function getTasksByUser($userId) {
        return $this->taskDAO->getTasksByUser($userId);  // Appel uniquement MySQL
    }

    // Supprimer une tâche en MySQL
    public function deleteTask($taskId) {
        $task = $this->taskDAO->getTaskById($taskId);
        if (!$task) {
            throw new Exception("Tâche non trouvée.");
        }

        // Supprimer en MySQL
        $this->taskDAO->deleteTask($taskId);
    }
}
?>
