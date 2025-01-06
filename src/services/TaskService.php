<?php
// On va créer un service qui interagit avec TaskDAO pour ajouter, récupérer, et manipuler les tâches.
require_once '../dao/TaskDAO.php';

class TaskService {
    private $taskDAO;

    public function __construct() {
        $this->taskDAO = new TaskDAO();  // Instancier le DAO pour interagir avec la base de données
    }

    // Ajouter une tâche
    public function addTask($title, $description, $userId) {
        if (empty($title)) {
            throw new Exception("Le titre de la tâche est obligatoire.");
        }

        // Ajouter la tâche
        $this->taskDAO->addTask($title, $description, $userId);
    }

    // Récupérer les tâches d'un utilisateur
    public function getTasksByUser($userId) {
        return $this->taskDAO->getTasksByUser($userId);
    }

    // Marquer une tâche comme terminée
    public function completeTask($taskId) {
        // Vérifier si la tâche existe, puis la marquer comme terminée
        $task = $this->taskDAO->getTaskById($taskId);
        if (!$task) {
            throw new Exception("Tâche non trouvée.");
        }
        $this->taskDAO->updateTaskStatus($taskId, 'completed');
    }

    // Supprimer une tâche
    public function deleteTask($taskId) {
        // Vérifier si la tâche existe, puis la supprimer
        $task = $this->taskDAO->getTaskById($taskId);
        if (!$task) {
            throw new Exception("Tâche non trouvée.");
        }
        $this->taskDAO->deleteTask($taskId);
    }
}
