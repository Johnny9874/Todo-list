<?php
namespace Services;

use Dao\TaskDAO;  // Assurez-vous que le namespace est correct
use Exception;

class TaskService {
    private $taskDAO;

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

        // L'utilisateur existe, on peut insérer la tâche
        $sql = "INSERT INTO tasks (title, description, user_id, priority, status, due_date, task_data) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $conn->error);
        }

        $stmt->bind_param("sssisss", $title, $description, $userId, $priority, $status, $due_date, $encoded_task_data);

        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        $taskId = $stmt->insert_id;  // Récupérer l'ID de la tâche insérée
        $stmt->close();

        // Retourner la tâche ajoutée
        return $this->getTaskById($taskId); 
    }

    // Récupérer les tâches par utilisateur
    public function getTasksByUser($userId) {
        return $this->taskDAO->getTasksByUser($userId);  // Appel uniquement MySQL
    }

    // Récupérer une tâche par son ID
    public function getTaskById($taskId) {
        return $this->taskDAO->getTaskById($taskId);
    }
}
?>
