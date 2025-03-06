<?php
require_once '../dao/TaskDAO.php';  // Inclure le fichier TaskDAO pour MySQL

class TaskService {
    private $taskDAO;  // Gestion MySQL

    public function __construct() {
        $this->taskDAO = new TaskDAO();  // Instancier MySQL
    }

    // Ajouter une tâche dans MySQL
    public function addTask($title, $description, $userId) {
        if (empty($title)) {
            throw new Exception("Le titre de la tâche est obligatoire.");
        }

        // Ajouter en MySQL
        $this->taskDAO->addTask($title, $description, $userId);
    }

    // Récupérer les tâches de MySQL
    public function getTasksByUser($userId, $source = "mysql") {
        if ($source === "mysql") {
            return $this->taskDAO->getTasksByUser($userId);
        }
    }
}

    // Supprimer une tâche en MySQL et MongoDB
    public function deleteTask($taskId) {
        $task = $this->taskDAO->getTaskById($taskId);
        if (!$task) {
            throw new Exception("Tâche non trouvée.");
        }

        // Supprimer en MySQL
        $this->taskDAO->deleteTask($taskId);
    }
?>
