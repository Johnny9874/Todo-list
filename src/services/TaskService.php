<?php
namespace Services;
use Services\TaskDAO;  // Ou le bon namespace

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
